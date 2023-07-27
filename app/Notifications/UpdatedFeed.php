<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Feed;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Laravel\Notifications\TelegramNotification;
use WeStacks\TeleBot\Objects\Message;

class UpdatedFeed extends Notification implements ShouldQueue
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
     * @return array<string>
     */
    public function via(mixed $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram(mixed $notifiable): PromiseInterface|Message|TelegramNotification
    {
        $text = "🗞 <b>Updated on {$this->feed->site->title}</b>
{$this->feed->title}
<a href=\"{$this->feed->link}\">Open in browser</a>
{$this->getAlternativeLinksToTelegram()}";

        if ($this->feed->photo) {
            return (new TelegramNotification)->bot('bot')
                ->sendPhoto([
                    'chat_id' => $notifiable->id,
                    'photo'   => $this->feed->photo,
                    'caption' => $text,
                    'parse_mode' => 'html',
                ]);
        }

        return (new TelegramNotification)->bot('bot')
            ->sendMessage([
                'chat_id' => $notifiable->id,
                'text'    => $text,
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
