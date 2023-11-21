<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Feed;
use App\Models\TelegramUser;
use App\Notifications\UpdatedFeed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendUpdatedFeedToTelegramUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Feed $item;

    /**
     * Create a new job instance.
     */
    public function __construct(Feed $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramUser::chunk(10, function ($telegramUsers): void {
            Notification::sendNow($telegramUsers, new UpdatedFeed($this->item));
        });
    }
}
