<?php

namespace App\Console\Commands;

use App\Models\Site;
use Carbon\Carbon;
use Feeds;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        Site::where('fed_at', '<=', now()->subMinutes(10))
            ->chunk(10, function ($sites) {
                $sites->each(function (Site $site) {
                    $feed = Feeds::make($site->link);
                    $items = $feed->get_items();
                    \DB::beginTransaction();
                    foreach ($items as $item) {
                        $this->info($item->get_title());
                        $site->feeds()->firstOrCreate([
                            'link' => $item->get_link(),
                            'published_at' => new Carbon($item->get_date())
                        ], [
                            'title' => $item->get_title(),
                            'link' => $item->get_link(),
                            'description' => $item->get_description(),
                            'published_at' => new Carbon($item->get_date())
                        ]);
                    }
                    $site->fed_at = now();
                    $site->save();
                    \DB::commit();
                });
            });

        return 0;
    }
}
