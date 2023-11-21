<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Site;

use App\Models\Site;
use App\Orchid\Layouts\Site\SiteListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class SiteListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'sites' => Site::filters()
                ->withCount('feeds')
                ->orderBy('created_at', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Manage sites';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Access rights';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.sites',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->href(route('platform.sites.create')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            SiteListLayout::class,
        ];
    }
}
