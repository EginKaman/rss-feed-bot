<?php

namespace App\Observers;

use App\Jobs\SendMessageTelegramUsers;
use App\Models\Feed;

class FeedObserver
{
    /**
     * Handle the Feed "created" event.
     *
     * @param Feed $feed
     * @return void
     */
    public function created(Feed $feed): void
    {
        dispatch(new SendMessageTelegramUsers($feed))->delay(10);
    }

    /**
     * Handle the Feed "updated" event.
     *
     * @param Feed $feed
     * @return void
     */
    public function updated(Feed $feed)
    {
        //
    }

    /**
     * Handle the Feed "deleted" event.
     *
     * @param Feed $feed
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
     * @return void
     */
    public function forceDeleted(Feed $feed)
    {
        //
    }
}
