<?php

declare(strict_types=1);

namespace App\Commands\Bot;

use App\Models\TelegramUser;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Send "/start" or "/s" to start use the bot';

    /**
     * @return void
     */
    public function handle(): void
    {
        $telegramUser = TelegramUser::query()
            ->firstOrNew([
                'id'            => $this->update->message->from->id,
            ], [
                'is_bot'        => $this->update->message->from->is_bot,
                'first_name'    => $this->update->message->from->first_name ?? null,
                'last_name'     => $this->update->message->from->last_name ?? null,
                'username'      => $this->update->message->from->username ?? null,
                'language_code' => $this->update->message->from->language_code,
            ]);
        $telegramUser->fill($this->update->message->from->toArray());
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

        try {
            $this->sendMessage([
                'text' => "Hello, {$this->update->message->from->first_name}!",
                'chat_id' => $this->update->message->from->id,
            ]);
        } catch (Exception $exception) {
            if (Str::contains($exception->getMessage(), 'bot was blocked by the user')) {
                TelegramUser::where('id', $this->update->message->from->id)->delete();
            }
            Log::error($exception->getMessage(), $exception->getTrace());
        }
    }
}
