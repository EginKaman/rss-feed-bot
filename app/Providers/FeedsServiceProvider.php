<?php

declare(strict_types=1);

namespace App\Providers;

use App\Factories\FeedsFactory;
use Illuminate\Support\ServiceProvider;
use RunTimeException;

class FeedsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     */
    protected bool $defer = true;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/feeds.php' => config_path('feeds.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(FeedsFactory::class, static function () {
            $config = config('feeds');

            if (! $config) {
                throw new RunTimeException('Feeds configuration not found.');
            }

            return new FeedsFactory($config);
        });
        $this->app->alias(FeedsFactory::class, 'Feeds');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['Feeds'];
    }
}
