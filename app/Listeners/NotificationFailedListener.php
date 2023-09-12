<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\TelegramUser;
use App\Notifications\NewFeed;
use App\Notifications\UpdatedFeed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Exceptions\TeleBotException;

class NotificationFailedListener
{
    public function __construct()
    {
    }

    public function handle($event): void
    {
        if (($event->notification instanceof NewFeed || $event->notification instanceof UpdatedFeed) && $event->notifiable instanceof TelegramUser) {
            foreach ($event->data as $datum) {
                if ($datum instanceof TeleBotException && Str::contains($datum->getMessage(),
                    'bot was blocked by the user')) {
                    $event->notifiable->delete();
                    Log::info('Telegram user deleted', ['id' => $event->notifiable->id]);
                } else {
                    $event->notifiable->notify($event->notifiable->delay(60));
                    Log::warning('Notification failed', ['message' => $datum->getMessage(), 'notifiable' => $event->notifiable, 'notification' => $event->notification]);
                }
            }
        }
    }
}
