<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Feed;

use App\Models\Feed;
use App\Orchid\Layouts\Feed\FeedListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class FeedListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'feeds' => Feed::with('site')->filters()->orderBy('created_at', 'desc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Manage feeds';
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
            'platform.feeds',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            FeedListLayout::class,
        ];
    }
}
