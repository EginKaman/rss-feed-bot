<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class FeedPresenter extends Presenter implements Searchable, Personable
{
    public function label(): string
    {
        return 'Feeds';
    }

    public function title(): string
    {
        return $this->entity->title;
    }

    public function subTitle(): string
    {
        return 'Feed';
    }

    public function url(): string
    {
        return $this->entity->link;
    }

    /**
     * @return ?string
     */
    public function image(): ?string
    {
        return null;
    }

    /**
     * The number of models to return for show compact search result.
     */
    public function perSearchShow(): int
    {
        return 3;
    }

    public function searchQuery(string $query = null): Builder
    {
        return $this->entity->search($query);
    }
}
