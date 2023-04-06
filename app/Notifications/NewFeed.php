<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Laravel\TelegramNotification;

class NewFeed extends Notification implements ShouldQueue
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
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramNotification
    {
        if ($this->feed->photo) {
            return (new TelegramNotification)->bot('bot')
                ->sendPhoto([
                    'chat_id' => $notifiable->id,
                    'photo'   => $this->feed->photo,
                    'caption' => "🗞 <b>New on {$this->feed->site->title}</b>
{$this->feed->title}
<a href=\"{$this->feed->link}\">Open in browser</a>",
                    'parse_mode' => 'html',
                ]);
        }

        return (new TelegramNotification)->bot('bot')
            ->sendMessage([
                'chat_id' => $notifiable->id,
                'text'    => "🗞 <b>New on {$this->feed->site->title}</b>
{$this->feed->title}
<a href=\"{$this->feed->link}\">Open in browser</a>
{$this->getAlternativeLinksToTelegram()}",
                'parse_mode' => 'html',
            ]);
    }

    public function getAlternativeLinksToTelegram(): string
    {
        $text = '';
        foreach ($this->feed->site->alternativeLinks as $alternativeLink) {
            $text .= PHP_EOL.'<a href="'.Str::replace($alternativeLink->replaceable_link, $alternativeLink->replaceable_link, $alternativeLink->site->link).'">Open on '.$alternativeLink->title.'</a>';
        }

        return $text;
    }
}
