<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\SendMessageTelegramUsers;
use App\Models\Feed;

class FeedObserver
{
    /**
     * Handle the Feed "created" event.
     */
    public function created(Feed $feed): void
    {
        dispatch(new SendMessageTelegramUsers($feed))->delay(30);
    }

    /**
     * Handle the Feed "updated" event.
     *
     * @return void
     */
    public function updated(Feed $feed)
    {
        //
    }

    /**
     * Handle the Feed "deleted" event.
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
     * @return void
     */
    public function restored(Feed $feed)
    {
        //
    }

    /**
     * Handle the Feed "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Feed $feed)
    {
        //
    }
}
