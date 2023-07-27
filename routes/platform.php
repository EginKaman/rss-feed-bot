<?php

declare(strict_types=1);

use App\Models\User;
use App\Orchid\Screens\Feed\FeedCardScreen;
use App\Orchid\Screens\Feed\FeedListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Site\SiteEditScreen;
use App\Orchid\Screens\Site\SiteListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, User $user) => $trail
        ->parent('platform.systems.users')
        ->push(__('User'), route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Role'), route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Platform > Sites
Route::screen('sites', SiteListScreen::class)
    ->name('platform.sites')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Sites'), route('platform.sites')));

// Platform > Sites > Edit
Route::screen('sites/{site}/edit', SiteEditScreen::class)
    ->name('platform.sites.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.sites')
        ->push(__('Role'), route('platform.sites.edit', $role)));

// Platform > Sites > Create
Route::screen('sites/create', SiteEditScreen::class)
    ->name('platform.sites.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.sites')
        ->push(__('Create'), route('platform.sites.create')));

// Platform > Sites
Route::screen('feeds', FeedListScreen::class)
    ->name('platform.feeds')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Feeds'), route('platform.feeds')));

//Route::screen('feeds/{feed}', FeedCardScreen::class)
//    ->name('platform.feeds')
//    ->breadcrumbs(fn(Trail $trail) => $trail
//        ->parent('platform.index')
//        ->push(__('Feeds'), route('platform.feeds')));

// Example...
//Route::screen('example', ExampleScreen::class)
//    ->name('platform.example')
//    ->breadcrumbs(fn (Trail $trail) => $trail
//        ->parent('platform.index')
//        ->push('Example screen'));
//
//Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
//Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
//Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
//Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
//Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
//Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');
