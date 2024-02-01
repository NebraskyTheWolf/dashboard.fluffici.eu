<?php

declare(strict_types=1);

use App\Orchid\Screens\Audit\AuditLogsListScreen;
use App\Orchid\Screens\Events\EventsEditScreen;
use App\Orchid\Screens\Events\EventsListScreen;
use App\Orchid\Screens\Pages\PagesEditScreen;
use App\Orchid\Screens\Pages\PagesListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Posts\PostEditScreen;
use App\Orchid\Screens\Posts\PostListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Shop\ShopProductEdit;
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
    ->name('main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Profile', route('platform.profile')));

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
        ->push('Create', route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Users', route('platform.systems.users')));

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
        ->push('Create', route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Roles', route('platform.systems.roles')));


Route::screen('post/{post?}', PostEditScreen::class)
    ->name('platform.post.edit')
    ->breadcrumbs(fn (Trail $trail, $post) => $trail
        ->parent('index')
        ->push('Edit', route('platform.post.edit', $post)));

Route::screen('posts', PostListScreen::class)
    ->name('platform.post.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Posts', route('platform.post.list')));

Route::screen('event/{events?}', EventsEditScreen::class)
    ->name('platform.events.edit');

Route::screen('events', EventsListScreen::class)
    ->name('platform.events.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Events', route('platform.events.list')));

Route::screen('socials', \App\Orchid\Screens\Social\SocialMedia::class)
    ->name('platform.social.list');

Route::screen('socials/edit/{social?}', \App\Orchid\Screens\Social\SocialMediaEdit::class)
    ->name('platform.social.edit');

Route::screen('page/{events?}', PagesEditScreen::class)
    ->name('platform.pages.edit');

Route::screen('pages', PagesListScreen::class)
    ->name('platform.pages.list');

Route::screen('audit', AuditLogsListScreen::class)
    ->name('platform.audit');

Route::screen('files', \App\Orchid\Screens\Attachments\AttachmentLists::class)
    ->name('platform.attachments');

Route::screen('files/platform/upload', \App\Orchid\Screens\Attachments\AttachmentUpload::class)
    ->name('platform.attachments.upload');

Route::screen('files/lookup/{file}', \App\Orchid\Screens\Attachments\AttachmentUpload::class)
    ->name('platform.attachments.lookup');

Route::screen('reports', \App\Orchid\Screens\Attachments\AttachmentReports::class)
    ->name('platform.reports');

Route::screen('shop/statistics', \App\Orchid\Screens\Shop\ShopStatistics::class)
    ->name('platform.shop.statistics');

Route::screen('shop/products', \App\Orchid\Screens\Shop\ShopProducts::class)
    ->name('platform.shop.products');

Route::screen('shop/products/edit/{products?}', ShopProductEdit::class)
    ->name('platform.shop.products.edit');

Route::screen('shop/categories', \App\Orchid\Screens\Shop\ShopCategories::class)
    ->name('platform.shop.categories');

Route::screen('shop/categories/edit/{category?}', \App\Orchid\Screens\Shop\ShopCategoryEdit::class)
    ->name('platform.shop.categories.edit');

Route::screen('shop/sales', \App\Orchid\Screens\Shop\ShopSales::class)
    ->name('platform.shop.sales');

Route::screen('shop/sales/edit/{sales?}', \App\Orchid\Screens\Shop\ShopSalesEdit::class)
    ->name('platform.shop.sales.edit');

Route::screen('shop/vouchers', \App\Orchid\Screens\Shop\ShopVouchers::class)
    ->name('platform.shop.vouchers');

Route::screen('shop/vouchers/edit/{voucher?}', \App\Orchid\Screens\Shop\ShopVoucherEdit::class)
    ->name('platform.shop.vouchers.edit');

Route::screen('shop/orders', \App\Orchid\Screens\Shop\ShopOrders::class)
    ->name('platform.shop.orders');

Route::screen('shop/orders/{order?}', \App\Orchid\Screens\Shop\ShopOrderEdit::class)
    ->name('platform.shop.orders.edit');

Route::screen('shop/support', \App\Orchid\Screens\Shop\ShopSupport::class)
    ->name('platform.shop.support');

Route::screen('shop/edit/settings', \App\Orchid\Screens\Shop\ShopSettings::class)
    ->name('platform.shop.settings');

Route::screen('shop/carriers', \App\Orchid\Screens\Shop\ShopCarrierList::class)
    ->name('platform.shop.carriers');

Route::screen('shop/carriers/edit/{carrier?}', \App\Orchid\Screens\Shop\ShopCarrierEdit::class)
    ->name('platform.shop.carriers.edit');

Route::screen('shop/countries', \App\Orchid\Screens\Shop\ShopCountriesList::class)
    ->name('platform.shop.countries.list');

Route::screen('shop/countries/edit/{country?}', \App\Orchid\Screens\Shop\ShopCountriesEdit::class)
    ->name('platform.shop.countries.edit');

Route::screen('shop/report/list', \App\Orchid\Screens\Shop\ShopReportList::class)
    ->name('platform.shop.reports');

Route::screen('accounting/main', \App\Orchid\Screens\Accounting\AccountingMain::class)
    ->name('platform.accounting.main');

Route::screen('accounting/new/{accounting?}', \App\Orchid\Screens\Accounting\AccountingMake::class)
    ->name('platform.accounting.new');

Route::screen('accounting/invoices', \App\Orchid\Screens\Accounting\AccountingInvoiceList::class)
    ->name('platform.accounting.invoices');

Route::screen('accounting/transactions', \App\Orchid\Screens\Accounting\AccountingTransactionsList::class)
    ->name('platform.accounting.transactions');


Route::screen('accounting/transactions/edit/{payment?}', \App\Orchid\Screens\Accounting\AccountingShopCreatePayment::class)
    ->name('platform.accounting.transactions.new');
