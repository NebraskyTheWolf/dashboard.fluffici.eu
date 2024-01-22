<?php

declare(strict_types=1);

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

use App\Orchid\Screens\Posts\PostEditScreen;
use App\Orchid\Screens\Posts\PostListScreen;

use App\Orchid\Screens\Events\EventsEditScreen;
use App\Orchid\Screens\Events\EventsListScreen;

use App\Orchid\Screens\Pages\PagesEditScreen;
use App\Orchid\Screens\Pages\PagesListScreen;

use App\Orchid\Screens\Audit\AuditLogsListScreen;

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
    ->name('main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

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
        ->parent('index')
        ->push(__('Roles'), route('platform.systems.roles')));


Route::screen('post/{post?}', PostEditScreen::class)
    ->name('platform.post.edit');

Route::screen('posts', PostListScreen::class)
    ->name('platform.post.list');

Route::screen('event/{events?}', EventsEditScreen::class)
    ->name('platform.events.edit');

Route::screen('events', EventsListScreen::class)
    ->name('platform.events.list');

Route::screen('page/{events?}', PagesEditScreen::class)
    ->name('platform.pages.edit');

Route::screen('pages', PagesListScreen::class)
    ->name('platform.pages.list');

Route::screen('audit', AuditLogsListScreen::class)
    ->name('platform.audit');

Route::screen('files', \App\Orchid\Screens\AttachmentLists::class)
    ->name('platform.attachments');

Route::screen('reports', \App\Orchid\Screens\AttachmentReports::class)
    ->name('platform.reports');

Route::screen('shop/statistics', \App\Orchid\Screens\Shop\ShopStatistics::class)
    ->name('platform.shop.statistics');

Route::screen('shop/products', \App\Orchid\Screens\Shop\ShopProducts::class)
    ->name('platform.shop.products');

Route::screen('shop/categories', \App\Orchid\Screens\Shop\ShopCategories::class)
    ->name('platform.shop.categories');

Route::screen('shop/sales', \App\Orchid\Screens\Shop\ShopSales::class)
    ->name('platform.shop.sales');

Route::screen('shop/vouchers', \App\Orchid\Screens\Shop\ShopVouchers::class)
    ->name('platform.shop.vouchers');

Route::screen('shop/orders', \App\Orchid\Screens\Shop\ShopOrders::class)
    ->name('platform.shop.orders');

Route::screen('shop/orders/{order?}', \App\Orchid\Screens\Shop\ShopOrders::class)
    ->name('platform.shop.orders.edit');

Route::screen('shop/support', \App\Orchid\Screens\Shop\ShopSupport::class)
    ->name('platform.shop.support');

Route::screen('shop/settings', \App\Orchid\Screens\Shop\ShopSettings::class)
    ->name('platform.shop.settings');
