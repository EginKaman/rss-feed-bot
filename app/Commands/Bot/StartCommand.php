<?php

declare(strict_types=1);

namespace App\Commands\Bot;

use App\Models\TelegramUser;
use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];

    protected static $description = 'Send "/start" or "/s" to start use the bot';

    public function handle(): void
    {
        $telegramUser = TelegramUser::query()
            ->firstOrNew([
                'id'            => $this->update->message->from->id,
                'is_bot'        => $this->update->message->from->is_bot,
                'first_name'    => $this->update->message->from->first_name,
                'last_name'     => $this->update->message->from->last_name,
                'username'      => $this->update->message->from->username,
                'language_code' => $this->update->message->from->language_code,
            ]);
        if ($this->update->message->from->is_bot) {
            $telegramUser->fill([
                'can_join_groups'             => $this->update->message->from->can_join_groups,
                'can_read_all_group_messages' => $this->update->message->from->can_read_all_group_messages,
                'support_inline_queries'      => $this->update->message->from->supports_inline_queries,
            ]);
        }
        if ($telegramUser->isDirty()) {
            $telegramUser->save();
        }
        $this->sendMessage([
            'text' => "Hello, {$this->update->message->from->first_name}!",
        ]);
    }
}
