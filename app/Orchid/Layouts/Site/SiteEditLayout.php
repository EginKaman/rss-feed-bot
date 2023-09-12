<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Site;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SiteEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('site.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name'))
                ->help(__('Role display name')),

            TextArea::make('site.description')
                ->max(1000)
                ->rows(10)
                ->title(__('Description'))
                ->placeholder(__('Description'))
                ->help(__('Site description')),

            Input::make('site.home_link')
                ->type('url')
                ->required()
                ->title(__('Home link'))
                ->placeholder(__('Home link'))
                ->help(__('Site home link')),

            Input::make('site.link')
                ->type('url')
                ->required()
                ->title(__('Link to RSS feed'))
                ->placeholder(__('Link'))
                ->help(__('Link to RSS feed')),
            Switcher::make('site.is_disabled')
                ->sendTrueOrFalse()
                ->title(__('Disabled'))
                ->placeholder(__('Disabled'))
                ->help(__('Disabled notifications from this site')),
        ];
    }
}
