<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Models\Site;
use JsonException;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class CallbackQueryHandler extends UpdateHandler
{
    /**
     * This function should return `true` if this handler should handle given update, or `false` if should not.
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->callback_query); // handle regular messages (example)
    }

    /**
     * This function should handle updates.
     *
     * @throws JsonException
     */
    public function handle(): void
    {
        $data = json_decode($this->update->callback_query->data, true, 512, JSON_THROW_ON_ERROR);
        if (! isset($data['p'])) {
            $this->answerCallbackQuery([
                'callback_query_id' => $this->update->callback_query->id,
                'text'              => 'Coming soon',
                'show_alert'        => true,
                'cache_time'        => 60,
            ]);

            return;
        }
        $sites = Site::paginate(5, ['*'], 'page', $data['p']);
        $keyboard = array_map(function ($site) {
            return [[
                'text'          => $site->title,
                'callback_data' => json_encode([
                    'a'  => 'sub',
                    'id' => $site->id,
                ], JSON_THROW_ON_ERROR),
            ]];
        }, $sites->items());
        if ($sites->lastPage() !== $data['p']) {
            $keyboard[][] = [
                'text'          => 'Next page',
                'callback_data' => json_encode([
                    'a' => 'site',
                    'p' => $sites->currentPage() + 1,
                ], JSON_THROW_ON_ERROR),
            ];
        }
        if ($data['p'] > 1) {
            $keyboard[][] = [
                'text'          => 'Prev page',
                'callback_data' => json_encode([
                    'a' => 'site',
                    'p' => $sites->currentPage() - 1,
                ], JSON_THROW_ON_ERROR),
            ];
        }
        $this->editMessageText([
            'chat_id'      => $this->update->callback_query->message->chat->id,
            'message_id'   => $this->update->callback_query->message->message_id,
            'text'         => "<b>Available sites for subscription:</b>\n",
            'parse_mode'   => 'html',
            'reply_markup' => [
                'inline_keyboard' => $keyboard,
                'auto_resize'     => true,
            ],
        ]);
    }
}
