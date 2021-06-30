<?php


namespace App\Commands\Bot;


use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Send "/start" or "/s" to get "Hello, World!"';

    public function handle()
    {
        $this->sendMessage([
            'text' => 'Hello, World!'
        ]);
    }
}
