<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

use App\Models\Events;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Settings')
                ->icon('bs.gear')
                ->title('Navigation')
                ->list([
                    Menu::make('Social Media')
                        ->icon('bs.person-walking')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.social'),
                    Menu::make("Users")
                        ->icon('bs.people')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.users')
                        ->title("Access Controls"),
                    Menu::make("Roles & Permissions")
                        ->icon('bs.shield')
                        ->route('platform.systems.roles')
                        ->permission('platform.systems.roles'),
                    Menu::make("Audit Logs")
                        ->icon('bs.clipboard2')
                        ->route('platform.audit')
                        ->permission('platform.audit.read')
                ])
                ->divider()
                ->permission('platform.systems.settings'),

            Menu::make('Attachments')
                ->icon('bs.archive')
                ->title('Attachments')
                ->list([
                    Menu::make('Files')
                        ->icon('bs.images')
                        ->route('platform.attachments')
                        ->permission('platform.systems.attachments.files'),
                    Menu::make("Reports & DMCA Request")
                        ->icon('bs.exclamation-octagon')
                        ->route('platform.reports')
                        ->permission('platform.systems.attachments.reports')
                ])
                ->divider()
                ->permission('platform.systems.attachments'),

            Menu::make('Main page')
                ->icon('bs.chat-right-text')
                ->list([
                    Menu::make('Posts')
                        ->icon('bs.book')
                        ->route('platform.post.list')
                        ->permission('platform.systems.posts'),
                    Menu::make('Events')
                        ->icon('bs.calendar-event')
                        ->route('platform.events.list')
                        ->slug('events')
                        ->badge(fn () => Events::where('status', 'INCOMING')->count() ?: 0)
                        ->permission('platform.systems.events'),
                    Menu::make('Pages')
                        ->icon('bs.file-earmark')
                        ->route('platform.pages.list')
                        ->permission('platform.systems.pages')
                ])
                ->divider()
                ->permission('platform.systems.posts'),

            Menu::make('E-Shop')
                ->icon('bs.cart2')
                ->list([
                    Menu::make('Statistics')
                        ->icon('bs.graph-up')
                        ->route('platform.shop.statistics')
                        ->title("GROWTH"),
                    Menu::make('Products')
                        ->icon('bs.window-sidebar')
                        ->route('platform.shop.products')
                        ->permission('platform.systems.eshop.products')
                        ->title("PRODUCTS AND SALES"),
                    Menu::make('Categories')
                        ->icon('bs.window-sidebar')
                        ->route('platform.shop.categories')
                        ->permission('platform.systems.eshop.products'),
                    Menu::make('Sales')
                        ->icon('bs.credit-card-2-front')
                        ->route('platform.shop.sales')
                        ->permission('platform.systems.eshop.sales')
                        ->canSee(false),
                    Menu::make('Vouchers')
                        ->icon('bs.card-list')
                        ->route('platform.shop.vouchers')
                        ->permission('platform.systems.eshop.vouchers')
                        ->title("VOUCHERS")
                        ->canSee(false),
                    Menu::make('Orders')
                        ->icon('bs.box-seam')
                        ->route('platform.shop.orders')
                        ->badge(fn () => ShopOrders::where('status', 'PENDING')->count() ?: 0)
                        ->permission('platform.systems.eshop.orders')
                        ->slug('orders')
                        ->title("ORDERS AND SUPPORT"),
                    Menu::make('Support Tickets')
                        ->icon('bs.chat-right-text')
                        ->slug('tickets')
                        ->route('platform.shop.support')
                        ->badge(fn () => ShopSupportTickets::where('status', 'PENDING')->count() ?: 0)
                        ->permission('platform.systems.eshop.support'),
                    Menu::make('Settings')
                        ->icon('bs.gear')
                        ->route('platform.shop.settings')
                        ->permission('platform.systems.eshop.settings')
                        ->title("SHOP MANAGEMENT")
                ])
                ->divider()
                ->permission('platform.systems.eshop')
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.systems.settings', "Settings (Navbar)")
                ->addPermission('platform.audit.read', "Audit Logs (read)")
                ->addPermission('platform.systems.social', "Social Media Management (read/write)"),

            ItemPermission::group("Shop Management")
                ->addPermission('platform.systems.eshop', "EShop (Navbar)")
                ->addPermission('platform.systems.eshop.settings', "EShop Settings (read/write)")
                ->addPermission('platform.systems.eshop.support', "EShop Support (read/write)")
                ->addPermission('platform.systems.eshop.orders', "EShop Orders (read/write)")
                ->addPermission('platform.systems.eshop.products', "EShop Products (read/write)")
                ->addPermission('platform.systems.eshop.vouchers', "EShop Vouchers (write)")
                ->addPermission('platform.systems.eshop.sales', "EShop Sales (read/write)"),

            ItemPermission::group('Attachments')
                ->addPermission('platform.systems.attachments.files', 'Files (Read)')
                ->addPermission('platform.systems.attachments.files.write', 'Files (Write)')
                ->addPermission('platform.systems.attachments.reports', 'Reports (Read)')
                ->addPermission('platform.systems.attachments.reports.write', 'Reports (Write)')
                ->addPermission('platform.systems.attachments', 'Attachments (Navbar)'),

            ItemPermission::group("Pages & Event management")
                ->addPermission('platform.systems.posts', "Posts (Navbar)")
                ->addPermission('platform.systems.events', "Events (Navbar)")
                ->addPermission('platform.systems.pages', "Pages (Navbar)")
                ->addPermission('platform.systems.gallery', "Photos (Navbar)")

                ->addPermission('platform.systems.pages.read', "Pages (read)")
                ->addPermission('platform.systems.pages.write', "Pages (write)")

                ->addPermission('platform.systems.events.read', "Events (read)")
                ->addPermission('platform.systems.events.write', "Events (write)")

                ->addPermission('platform.systems.post.read', "Posts (read)")
                ->addPermission('platform.systems.post.write', "Posts (write)")
        ];
    }
}
