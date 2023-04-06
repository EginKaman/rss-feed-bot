<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Site;

use App\Models\Site;
use App\Orchid\Layouts\Site\SiteEditLayout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SiteEditScreen extends Screen
{
    public Site $site;

    /**
     * Fetch data to be displayed on the screen.
     *
     *
     * @return array
     */
    public function query(Site $site): iterable
    {
        return [
            'site' => $site,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Manage site';
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
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->site->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([
                SiteEditLayout::class,
            ])
                ->title('Site')
                ->description('A role is a collection of privileges (of possibly different services like the Users service, Moderator, and so on) that grants users with that role the ability to perform certain tasks or operations.'),

            //            Layout::block([
            //                RolePermissionLayout::class,
            //            ])
            //                ->title('Permission/Privilege')
            //                ->description('A privilege is necessary to perform certain tasks and operations in an area.'),
        ];
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Access rights';
    }

    /**
     * @return RedirectResponse
     */
    public function save(Request $request, Site $site)
    {
        $request->validate([
            'site.title' => [
                'required',
                'string',
                'max:255',
            ],
            'site.description' => [
                'required',
                'string',
            ],
            'site.home_link' => [
                'required',
                'url',
            ],
            'site.link' => [
                'required',
                'url',
            ],
        ]);

        $site->fill($request->get('site'));

        $site->save();

        Toast::info(__('Site was saved'));

        return redirect()->route('platform.sites');
    }

    /**
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function remove(Site $site)
    {
        $site->delete();

        Toast::info(__('Site was removed'));

        return redirect()->route('platform.sites');
    }
}
