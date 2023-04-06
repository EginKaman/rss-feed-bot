<?php

declare(strict_types=1);

namespace App\Commands\Bot;

use App\Models\Site;
use Illuminate\Support\Facades\Log;
use JsonException;
use WeStacks\TeleBot\Handlers\CommandHandler;

class SitesCommand extends CommandHandler
{
    /**
     * Command aliases.
     *
     * @var string[]
     */
    protected static $aliases = ['/sites'];

    /**
     * Command description.
     *
     * @var string
     */
    protected static $description = 'Send "/sites" to get all available sites for subscription!';

    protected string $text = "<b>Available sites for subscription:</b>\n";

    /**
     * This function should handle updates.
     *
     * @throws JsonException
     */
    public function handle(): void
    {
        $sites = Site::paginate(5);
        $keyboard = array_map(call_user_func(self::mapSite(...)), $sites->items());
        $keyboard[][] = $this->getNextPageKeyboard($sites);
        $this->sendMessage(
            [
                'text'         => $this->text,
                'parse_mode'   => 'html',
                'reply_markup' => [
                    'inline_keyboard' => $keyboard,
                    'auto_resize'     => true,
                ],
            ]
        );
    }

    /**
     * @throws JsonException
     */
    protected static function mapSite(Site $site): array
    {
        return [
            [
                'text'          => $site->title,
                'callback_data' => json_encode([
                    'a'  => 'sub',
                    'id' => $site->id,
                ], JSON_THROW_ON_ERROR),
            ],
        ];
    }

    protected function getNextPageKeyboard($sites): array
    {
        try {
            return [
                'text'          => 'Next page',
                'callback_data' => json_encode([
                    'a' => 'site',
                    'p' => $sites->currentPage() + 1,
                ], JSON_THROW_ON_ERROR),
            ];
        } catch (JsonException $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            $this->sendMessage(
                [
                    'text' => 'An unknown error occurred. Try again later.',
                ]
            );
        }
    }
}