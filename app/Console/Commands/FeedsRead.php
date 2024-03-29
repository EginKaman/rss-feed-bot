<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Site;
use DOMDocument;
use Feeds;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimplePie\SimplePie;

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
            ->where(function ($query) {
                $query->where('pauses_at', '<=', now())
                    ->orWhereNull('pauses_at');
            })
            ->where(function ($query) {
                $query->where('fed_at', '<=', now()->subMinutes(10))
                    ->orWhereNull('fed_at');
            })
            ->orderBy('fed_at', 'desc')
            ->chunk(100, function ($sites) {
                $sites->each(function (Site $site) {
                    $site->authentications->each(function ($authentication) {
                        $client = Http::asForm()
                            ->withUserAgent(config('feeds.user_agent'))
                            ->post($authentication->login_url, array_merge([
                                $authentication->login_field    => $authentication->login,
                                $authentication->password_field => $authentication->password,
                            ], $authentication->additional_fields));
                        if ($client->failed()) {
                            return true;
                        }
                        $this->cookies = $client->cookies()->toArray();

                        return false;
                    });
                    $curlOptions = [
                        CURLOPT_HTTPHEADER => [
                            'Accept: application/atom+xml, application/rss+xml, application/rdf+xml;q=0.9, application/xml;q=0.8, text/xml;q=0.8, text/html;q=0.7, unknown/unknown;q=0.1, application/unknown;q=0.1, */*;q=0.1',
                        ],
                        CURLOPT_REFERER => $site->home_link,
                    ];
                    if ($this->cookies !== []) {
                        $curlOptions[CURLOPT_COOKIE] = implode(';', array_map(static function ($k, $v): string {
                            return "{$v['Name']}=".rawurlencode($v['Value']);
                        }, array_keys($this->cookies), array_values($this->cookies)));
                    } else {
                        $curlOptions[CURLOPT_COOKIE] = 'wp-wpml_current_language=uk;';
                    }
                    /** @var SimplePie $feed */
                    $feed = Feeds::make($site->link, 0, true, [
                        'curl.options' => $curlOptions + config('feeds')['curl.options'],
                    ]);

                    if (Str::contains($feed->raw_data, 'xmlns="https://cyber.harvard.edu/rss/rss.html"')) {
                        $rawData = str_replace('xmlns="https://cyber.harvard.edu/rss/rss.html"', 'xmlns:content="http://purl.org/rss/1.0/modules/content/"', $feed->raw_data);
                        $feed = new SimplePie();
                        $feed->set_raw_data($rawData);
                        $feed->set_item_limit(0);

                        $stripHtmlTags = Arr::get(config('feeds'), 'strip_html_tags.disabled', false);

                        if (! $stripHtmlTags && ! empty(config('feeds.strip_html_tags.tags')) && is_array(config('feeds.strip_html_tags.tags'))) {
                            $feed->strip_htmltags(config('feeds.strip_html_tags.tags'));
                        } else {
                            $feed->strip_htmltags(false);
                        }

                        if (! $stripHtmlTags && ! empty(config('feeds.strip_attribute.tags')) && is_array(config('feeds.strip_attribute.tags'))) {
                            $feed->strip_attributes(config('feeds.strip_attribute.tags'));
                        } else {
                            $feed->strip_attributes(false);
                        }

                        if (! empty(config('feeds.curl.timeout')) && is_int(config('feeds.curl.timeout'))) {
                            $feed->set_timeout(config('feeds.curl.timeout'));

                        }

                        $feed->init();
                    }

                    $items = $feed->get_items();
                    if (count($items) === 0) {
                        Log::info('No feeds for '.$site->link, ['error' => $feed->error()]);
                        if ($site->pauses_at !== null) {
                            $site->pauses_at->addHours(3);
                        } else {
                            $site->pauses_at = now()->addMinutes(30);
                        }
                        $site->save();

                        return true;
                    }
                    foreach ($items as $item) {
                        $enclosure = $item->get_enclosure();
                        $photo = $enclosure->get_link() ?? $enclosure->get_thumbnail() ?? null;
                        $description = $item->get_description() ?? $item->get_content() ?? $enclosure->get_description() ?? null;
                        if ($photo === null && $description !== null) {
                            $dom = new DOMDocument();
                            libxml_use_internal_errors(true);
                            $dom->loadHTML($description);
                            $imagesTags = $dom->getElementsByTagName('img');
                            if ($imagesTags->count()) {
                                $photo = $imagesTags->item(0)->attributes->getNamedItem('src')?->nodeValue;
                            }
                        }
                        if ($photo !== null) {
                            $photo = str_replace('&amp;', '&', $photo);
                        }

                        $this->info($item->get_title());
                        $feed = $site->feeds()->firstOrNew([
                            'link' => $item->get_link(),
                        ]);

                        $feed->fill([
                            'title'        => $item->get_title(),
                            'photo'        => $photo,
                            'link'         => $item->get_link(),
                            'description'  => $description,
                            'published_at' => (new Carbon($item->get_date()))->setTimezone('UTC'),
                        ]);

                        if ($feed->isDirty(['title', 'published_at']) || ! $feed->exists) {
                            $feed->save();
                        }
                    }
                    $site->fed_at = now();
                    $site->pauses_at = null;
                    $site->save();
                    $this->cookies = [];

                    return true;
                });
            });

        return self::SUCCESS;
    }
}
