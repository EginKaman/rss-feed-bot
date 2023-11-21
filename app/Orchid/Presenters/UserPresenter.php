<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class UserPresenter extends Presenter implements Personable, Searchable
{
    public function label(): string
    {
        return 'Users';
    }

    public function title(): string
    {
        return $this->entity->name;
    }

    public function subTitle(): string
    {
        $roles = $this->entity->roles->pluck('name')->implode(' / ');

        return (string) Str::of($roles)
            ->limit(20)
            ->whenEmpty(static fn () => __('Regular user'));
    }

    public function url(): string
    {
        return route('platform.systems.users.edit', $this->entity);
    }

    public function image(): ?string
    {
        $hash = md5(mb_strtolower(trim($this->entity->email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=mp";
    }

    /**
     * The number of models to return for show compact search result.
     */
    public function perSearchShow(): int
    {
        return 3;
    }

    public function searchQuery(?string $query = null): Builder
    {
        return $this->entity->search($query);
    }
}
