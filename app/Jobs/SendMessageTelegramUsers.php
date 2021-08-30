<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\TelegramUser;
use App\Notifications\NewFeed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageTelegramUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Feed $item;

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
            $telegramUsers->each(function (TelegramUser $telegramUser) {
                $telegramUser->notify(new NewFeed($this->item));
            });
        });
    }
}
