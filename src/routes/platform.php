<?php

declare(strict_types=1);

use App\Orchid\Screens\Accounting\AccountingInvoiceList;
use App\Orchid\Screens\Accounting\AccountingMain;
use App\Orchid\Screens\Accounting\AccountingMake;
use App\Orchid\Screens\Accounting\AccountingReportList;
use App\Orchid\Screens\Accounting\AccountingShopCreatePayment;
use App\Orchid\Screens\Accounting\AccountingTransactionsList;
use App\Orchid\Screens\Accounting\AccountingTRSReport;
use App\Orchid\Screens\Attachments\AttachmentLists;
use App\Orchid\Screens\Attachments\AttachmentReportReview;
use App\Orchid\Screens\Attachments\AttachmentReports;
use App\Orchid\Screens\Attachments\AttachmentUpload;
use App\Orchid\Screens\Audit\AuditLogsListScreen;
use App\Orchid\Screens\Devices\DeviceEditScreen;
use App\Orchid\Screens\Devices\DeviceScreen;
use App\Orchid\Screens\Email\SendEmail;
use App\Orchid\Screens\Events\EventsEditScreen;
use App\Orchid\Screens\Events\EventsListScreen;
use App\Orchid\Screens\Pages\PagesEditScreen;
use App\Orchid\Screens\Pages\PagesListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Posts\PostEditScreen;
use App\Orchid\Screens\Posts\PostListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Shop\CustomerEditScreen;
use App\Orchid\Screens\Shop\CustomerListScreen;
use App\Orchid\Screens\Shop\ShopCarrierEdit;
use App\Orchid\Screens\Shop\ShopCarrierList;
use App\Orchid\Screens\Shop\ShopCategories;
use App\Orchid\Screens\Shop\ShopCategoryEdit;
use App\Orchid\Screens\Shop\ShopCountriesEdit;
use App\Orchid\Screens\Shop\ShopCountriesList;
use App\Orchid\Screens\Shop\ShopOrderEdit;
use App\Orchid\Screens\Shop\ShopOrders;
use App\Orchid\Screens\Shop\ShopProductEdit;
use App\Orchid\Screens\Shop\ShopProducts;
use App\Orchid\Screens\Shop\ShopReportList;
use App\Orchid\Screens\Shop\ShopSales;
use App\Orchid\Screens\Shop\ShopSalesEdit;
use App\Orchid\Screens\Shop\ShopSettings;
use App\Orchid\Screens\Shop\ShopStatistics;
use App\Orchid\Screens\Shop\ShopSupport;
use App\Orchid\Screens\Shop\ShopVoucherEdit;
use App\Orchid\Screens\Shop\ShopVouchers;
use App\Orchid\Screens\Shop\TaxGroupEdit;
use App\Orchid\Screens\Shop\TaxGroupList;
use App\Orchid\Screens\Social\SocialMedia;
use App\Orchid\Screens\Social\SocialMediaEdit;
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
    ->name('platform.post.edit');

Route::screen('posts', PostListScreen::class)
    ->name('platform.post.list');

Route::screen('event/{events?}', EventsEditScreen::class)
    ->name('platform.events.edit');

Route::screen('events', EventsListScreen::class)
    ->name('platform.events.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('index')
        ->push('Events', route('platform.events.list')));

Route::screen('socials', SocialMedia::class)
    ->name('platform.social.list');

Route::screen('socials/edit/{social?}', SocialMediaEdit::class)
    ->name('platform.social.edit');

Route::screen('page/{events?}', PagesEditScreen::class)
    ->name('platform.pages.edit');

Route::screen('pages', PagesListScreen::class)
    ->name('platform.pages.list');

Route::screen('audit', AuditLogsListScreen::class)
    ->name('platform.audit');

Route::screen('files', AttachmentLists::class)
    ->name('platform.attachments');

Route::screen('files/platform/upload', AttachmentUpload::class)
    ->name('platform.attachments.upload');

Route::screen('files/lookup/{file}', AttachmentUpload::class)
    ->name('platform.attachments.lookup');

Route::screen('reports', AttachmentReports::class)
    ->name('platform.reports');
Route::screen('report/review/{case?}', AttachmentReportReview::class)
    ->name('platform.attachments.review');

Route::screen('shop/statistics', ShopStatistics::class)
    ->name('platform.shop.statistics');

Route::screen('shop/products', ShopProducts::class)
    ->name('platform.shop.products');

Route::screen('shop/products/edit/{products?}', ShopProductEdit::class)
    ->name('platform.shop.products.edit');

Route::screen('shop/categories', ShopCategories::class)
    ->name('platform.shop.categories');

Route::screen('shop/categories/edit/{category?}', ShopCategoryEdit::class)
    ->name('platform.shop.categories.edit');

Route::screen('shop/sales', ShopSales::class)
    ->name('platform.shop.sales');

Route::screen('shop/sales/edit/{sales?}', ShopSalesEdit::class)
    ->name('platform.shop.sales.edit');

Route::screen('shop/vouchers', ShopVouchers::class)
    ->name('platform.shop.vouchers');

Route::screen('shop/vouchers/edit/{voucher?}', ShopVoucherEdit::class)
    ->name('platform.shop.vouchers.edit');

Route::screen('shop/orders', ShopOrders::class)
    ->name('platform.shop.orders');

Route::screen('shop/orders/{order?}', ShopOrderEdit::class)
    ->name('platform.shop.orders.edit');

Route::screen('shop/support', ShopSupport::class)
    ->name('platform.shop.support');

Route::screen('shop/edit/settings', ShopSettings::class)
    ->name('platform.shop.settings');

Route::screen('shop/carriers', ShopCarrierList::class)
    ->name('platform.shop.carriers');

Route::screen('shop/carriers/edit/{carrier?}', ShopCarrierEdit::class)
    ->name('platform.shop.carriers.edit');

Route::screen('shop/countries', ShopCountriesList::class)
    ->name('platform.shop.countries.list');

Route::screen('shop/countries/edit/{country?}', ShopCountriesEdit::class)
    ->name('platform.shop.countries.edit');

Route::screen('shop/report/list', ShopReportList::class)
    ->name('platform.shop.reports');

Route::screen('accounting/main', AccountingMain::class)
    ->name('platform.accounting.main');

Route::screen('accounting/new/{accounting?}', AccountingMake::class)
    ->name('platform.accounting.new');

Route::screen('accounting/invoices', AccountingInvoiceList::class)
    ->name('platform.accounting.invoices');

Route::screen('accounting/transactions', AccountingTransactionsList::class)
    ->name('platform.accounting.transactions');

Route::screen('accounting/report/list', AccountingReportList::class)
    ->name('platform.accounting.reports');

Route::screen('accounting/transactions/edit/{payment?}', AccountingShopCreatePayment::class)
    ->name('platform.accounting.transactions.new');

Route::screen('accounting/transactions/reports', AccountingTRSReport::class)
    ->name('platform.accounting.transactions.reports');

Route::screen('shop/tax/edit/{group?}', TaxGroupEdit::class)
    ->name('tax.edit.group');

Route::screen('shop/taxes', TaxGroupList::class)
    ->name('tax.group.list');

Route::screen('shop/customers/list', CustomerListScreen::class)
    ->name('platform.shop.customers');

Route::screen('shop/customers/edit/{customer?}', CustomerEditScreen::class)
    ->name('platform.shop.customer.edit');

Route::screen('email/send', SendEmail::class)
    ->name('platform.admin.sendmail');

Route::screen('devices', DeviceScreen::class)
    ->name('platform.device');

Route::screen('devices/new/{device?}', DeviceEditScreen::class)
    ->name('platform.device.new');
