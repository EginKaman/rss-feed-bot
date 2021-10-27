<?php

namespace App\Console\Commands;

use App\Models\Site;
use Carbon\Carbon;
use DOMDocument;
use Feeds;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FeedsRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read new feeds';

    private array $cookies = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        Site::query()
            ->with('authentications')
            ->where('fed_at', '<=', now()->subMinutes(10))
            ->chunk(10, function ($sites) {
                $sites->each(function (Site $site) {
                    $site->authentications->each(function ($authentication) {
                        $client = Http::asForm()
                            ->withUserAgent(config('feeds.user_agent'))
                            ->post($authentication->login_url, array_merge([
                                $authentication->login_field => $authentication->login,
                                $authentication->password_field => $authentication->password
                            ], $authentication->additional_fields));
                        if ($client->failed()) {
                            return true;
                        }
                        $this->cookies = $client->cookies()->toArray();
                        return false;
                    });
                    $cookies = array_map(static function ($k, $v): string {
                        return "{$v['Name']}=" . rawurlencode($v['Value']);
                    }, array_keys($this->cookies), array_values($this->cookies));
                    $feed = Feeds::make($site->link, 0, false, [
                        'curl.options' => [
                            CURLOPT_COOKIE => implode(';', $cookies),
                        ]
                    ]);
                    $items = $feed->get_items();
                    DB::beginTransaction();
                    foreach ($items as $item) {
                        $photo = null;
                        if ($item->get_enclosure()->get_link()) {
                            $photo = $item->get_enclosure()->get_link();
                        } else {
                            $dom = new DOMDocument();
                            libxml_use_internal_errors(true);
                            $dom->loadHTML($item->get_description());
                            $imgs = $dom->getElementsByTagName('img');
                            if ($imgs->count()) {
                                $photo = $imgs->item(0)->attributes->getNamedItem('src')->nodeValue;
                            }
                        }
                        $this->info($item->get_title());
                        $site->feeds()->firstOrCreate([
                            'link' => $item->get_link(),
                            'published_at' => new Carbon($item->get_date())
                        ], [
                            'title' => $item->get_title(),
                            'photo' => $photo,
                            'link' => $item->get_link(),
                            'description' => $item->get_description(),
                            'published_at' => new Carbon($item->get_date())
                        ]);
                    }
                    $site->fed_at = now();
                    $site->save();
                    try {
                        DB::commit();
                    } catch (Throwable $e) {
                        DB::rollBack(0);
                    }
                });
            });

        return 0;
    }
}
