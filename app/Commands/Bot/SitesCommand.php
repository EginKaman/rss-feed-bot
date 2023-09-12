<?php

declare(strict_types=1);

namespace App\Commands\Bot;

use App\Models\Site;
use Illuminate\Support\Arr;
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
     *
     * @return void
     */
    public function handle(): void
    {
        $sites = Site::paginate(5);
        $keyboard = Arr::map($sites->items(), fn (Site $site) => [
            [
                'text'          => $site->title,
                'callback_data' => json_encode([
                    'a'  => 'sub',
                    'id' => $site->id,
                ], JSON_THROW_ON_ERROR),
            ],
        ]);
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
     * @param Site $site
     *
     * @throws JsonException
     *
     * @return array
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

    /**
     * @param $sites
     *
     * @return array
     */
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

            return [];
        }
    }
}
