<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FeedsRemoveDuplicatesCommand extends Command
{
    protected $signature = 'feeds:remove-duplicates';

    protected $description = 'Command description';

    public function handle(): int
    {
        $duplicatedFeeds = Feed::select(['link', DB::raw('count(*) as count')])
            ->groupBy('link')
            ->havingRaw('count(*) > 1')->get();

        $duplicatedFeeds->each(function (Feed $feed) {
            $this->info("$feed->link with count: $feed->count");
            $duplicatedIds = Feed::where('link',
                $feed->link)->orderBy('created_at')->limit($feed->count - 1)->pluck('id');
            $this->info('Will be deleted: '.$duplicatedIds->count());
            Feed::whereIn('id', $duplicatedIds)->delete();
        });

        return self::SUCCESS;
    }
}
