<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Examples;

use App\Orchid\Layouts\Examples\TabMenuExample;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Throwable;

class ExampleLayoutsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Overview layouts';
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
     * @throws Throwable
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(Layout::view('platform::dummy.block'))
                ->title('Block header')
                ->description('Excellent description that editing or views in block'),

            Layout::tabs([
                'Example Tab 1' => Layout::view('platform::dummy.block'),
                'Example Tab 2' => Layout::view('platform::dummy.block'),
                'Example Tab 3' => Layout::view('platform::dummy.block'),
            ]),

            TabMenuExample::class,
            Layout::view('platform::dummy.block'),

            Layout::columns([
                Layout::view('platform::dummy.block'),
                Layout::view('platform::dummy.block'),
                Layout::view('platform::dummy.block'),
            ]),

            Layout::accordion([
                'Collapsible Group Item #1' => Layout::view('platform::dummy.block'),
                'Collapsible Group Item #2' => Layout::view('platform::dummy.block'),
                'Collapsible Group Item #3' => Layout::view('platform::dummy.block'),
            ]),

        ];
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Components for laying out your project';
    }
}
