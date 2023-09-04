<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\TelegramUser;
use App\Notifications\NewFeed;
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
        if ($event->notification instanceof NewFeed && $event->notifiable instanceof TelegramUser) {
            foreach ($event->data as $datum) {
                if ($datum instanceof TeleBotException && Str::contains($datum->getMessage(),
                    'bot was blocked by the user')) {
                    $event->notifiable->delete();
                    Log::info('Telegram user deleted', ['id' => $event->notifiable->id]);
                } else {
                    Log::warning('Notification failed', ['data' => $event->data, 'notifiable' => $event->notifiable, 'notification' => $event->notification]);
                }
            }
        }
    }
}
