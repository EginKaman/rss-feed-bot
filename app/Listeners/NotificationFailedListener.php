<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\TelegramUser;
use App\Notifications\NewFeed;
use App\Notifications\UpdatedFeed;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Exceptions\TeleBotException;

class NotificationFailedListener
{
    protected array $wrongFile = [
        'wrong file identifier/HTTP URL specified',
        'wrong type of the web page content',
    ];

    public function __construct()
    {
    }

    public function handle(NotificationFailed $event): void
    {
        if (($event->notification instanceof NewFeed || $event->notification instanceof UpdatedFeed) && $event->notifiable instanceof TelegramUser) {
            foreach ($event->data as $datum) {
                if ($datum instanceof TeleBotException && Str::contains($datum->getMessage(),
                    ['bot was blocked by the user', 'user is deactivated'])) {
                    $event->notifiable->delete();
                    Log::info('Telegram user deleted', ['id' => $event->notifiable->id]);

                    return;
                }

                if ($datum instanceof TeleBotException && Str::contains($datum->getMessage(), $this->wrongFile)) {
                    $event->notifiable->notify($event->notification->withoutImage()->delay(60));
                } else {
                    $event->notifiable->notify($event->notification->delay(600));
                }
                Log::warning('Notification failed', [
                    'message'      => $datum->getMessage(),
                    'notifiable'   => $event->notifiable,
                    'notification' => $event->notification,
                ]);
            }
        }
    }
}
