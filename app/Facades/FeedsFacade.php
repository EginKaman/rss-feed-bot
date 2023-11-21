<?php

declare(strict_types=1);

namespace App\Facades;

use App\Factories\FeedsFactory;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @mixin FeedsFactory
 */
class FeedsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Feeds';
    }
}
