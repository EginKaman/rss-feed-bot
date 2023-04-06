<?php

declare(strict_types=1);

namespace App\Commands\Bot;

use WeStacks\TeleBot\Handlers\CommandHandler;

class HelloCommand extends CommandHandler
{
    protected static $aliases = ['/hello', '/h'];
    protected static $description = 'Send "/hello" or "/h" to get "Hello, {user}!"';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->sendMessage([
            'text' => "Hello, {$this->update->message->chat->first_name}!",
        ]);
    }
}
