<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\SendNewFeedToTelegramUsers;
use App\Jobs\SendUpdatedFeedToTelegramUsers;
use App\Models\Feed;

class FeedObserver
{
    /**
     * Handle the Feed "created" event.
     *
     * @param Feed $feed
     *
     * @return void
     */
    public function created(Feed $feed): void
    {
        if ($feed->site->is_disabled) {
            return;
        }
        dispatch(new SendNewFeedToTelegramUsers($feed))->onQueue('telegram-new');
    }

    /**
     * Handle the Feed "updated" event.
     *
     * @param Feed $feed
     *
     * @return void
     */
    public function updated(Feed $feed): void
    {
        if ($feed->site->is_disabled) {
            return;
        }
        dispatch(new SendUpdatedFeedToTelegramUsers($feed))->onQueue('telegram-update');
    }

    /**
     * Handle the Feed "deleted" event.
     *
     * @param Feed $feed
     *
     * @return void
     */
    public function deleted(Feed $feed)
    {
        //
    }

    /**
     * Handle the Feed "restored" event.
     *
     * @param Feed $feed
     *
     * @return void
     */
    public function restored(Feed $feed)
    {
        //
    }

    /**
     * Handle the Feed "force deleted" event.
     *
     * @param Feed $feed
     *
     * @return void
     */
    public function forceDeleted(Feed $feed)
    {
        //
    }
}
