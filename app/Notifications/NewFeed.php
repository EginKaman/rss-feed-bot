<?php

namespace App\Notifications;

use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use WeStacks\TeleBot\Laravel\TelegramNotification;

class NewFeed extends Notification
{
    use Queueable;

    private Feed $feed;

    /**
     * Create a new notification instance.
     *
     * @param Feed $feed
     */
    public function __construct(Feed $feed)
    {
        $this->feed = $feed->load('site');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramNotification
    {
        return (new TelegramNotification)->bot('bot')
            ->sendMessage([
                'chat_id' => $notifiable->id,
                'text' => "<a href=\"{$this->feed->link}\">🗞 New on {$this->feed->site->title}</a>
{$this->feed->title}

{$this->feed->description}

<a href=\"{$this->feed->link}\">Open in browser</a>",
                'parse_mode' => 'html'
            ]);
    }

}
