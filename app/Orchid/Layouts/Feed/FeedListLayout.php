<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Feed;

use App\Models\Feed;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FeedListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'feeds';

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
                ->render(fn (Feed $feed) => Link::make($feed->title)
                    ->route('platform.sites.edit', $feed->id)),

            TD::make('link', __('Link'))
                ->sort()
                ->cantHide()
                ->render(fn (Feed $feed) => Link::make(__('Open'))
                    ->href($feed->link)->target('_blank')),

            TD::make('site', __('Site'))
                ->render(function (Feed $feed) {
                    return e($feed->site->title);
                })
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('created_at', __('Created'))
                ->sort()
                ->render(fn (Feed $feed) => $feed->created_at->toDateTimeString()),
        ];
    }
}
