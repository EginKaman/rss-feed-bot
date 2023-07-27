<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Site;

use App\Models\Site;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SiteListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'sites';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', __('Title'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Site $site) => Link::make($site->title)
                    ->route('platform.sites.edit', $site->id)),
            TD::make('feeds_count', __('Feeds count'))
                ->sort(),
            TD::make('home_link', __('Home link'))
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Site $site) => Link::make(__('Open'))
                    ->href($site->home_link)->target('_blank')),

            TD::make('fed_at', __('Fed'))
                ->sort()
                ->render(fn (Site $site) => $site->fed_at?->toDateTimeString()),

            TD::make('created_at', __('Created'))
                ->sort()
                ->render(fn (Site $site) => $site->created_at->toDateTimeString()),
        ];
    }
}
