<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Feed;
use App\Models\TelegramUser;
use App\Notifications\NewFeed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNewFeedToTelegramUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Feed $item;

    /**
     * Create a new job instance.
     *
     * @param Feed $item
     */
    public function __construct(Feed $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        TelegramUser::chunk(10, function ($telegramUsers) {
            Notification::sendNow($telegramUsers, new NewFeed($this->item));
        });
    }
}
