<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Events;
use App\Models\ShopOrders;
use App\Models\ShopSettings;
use App\Models\ShopSupportTickets;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

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
            Menu::make('Nastavení')
                ->icon('bs.gear')
                ->title('Navigation')
                ->list([
                    Menu::make('Sociální sítě')
                        ->icon('bs.person-walking')
                        ->route('platform.social.list')
                        ->permission('platform.systems.social'),
                    Menu::make("Uživatelé")
                        ->icon('bs.people')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.users')
                        ->title("Povolení Řízení Přístupu"),
                    Menu::make("Role a oprávnění")
                        ->icon('bs.shield')
                        ->route('platform.systems.roles')
                        ->permission('platform.systems.roles'),
                    Menu::make("Protokoly o auditu")
                        ->icon('bs.clipboard2')
                        ->route('platform.audit')
                        ->permission('platform.audit.read')
                ])
                ->divider()
                ->permission('platform.systems.settings'),

            Menu::make('Přílohy')
                ->icon('bs.archive')
                ->list([
                    Menu::make('Soubory')
                        ->icon('bs.images')
                        ->route('platform.attachments')
                        ->permission('platform.systems.attachments.files'),
                    Menu::make("Reports & DMCA")
                        ->icon('bs.exclamation-octagon')
                        ->route('platform.reports')
                        ->permission('platform.systems.attachments.reports')
                ])
                ->divider()
                ->permission('platform.systems.attachments'),

            Menu::make('Main page')
                ->icon('bs.chat-right-text')
                ->list([
                    Menu::make('Zprávy')
                        ->icon('bs.book')
                        ->route('platform.post.list')
                        ->permission('platform.systems.posts'),
                    Menu::make('Akce')
                        ->icon('bs.calendar-event')
                        ->route('platform.events.list')
                        ->slug('events')
                        ->badge(fn () => Events::where('status', 'INCOMING')->count() ?: 0)
                        ->permission('platform.systems.events'),
                    Menu::make('Stranky')
                        ->icon('bs.file-earmark')
                        ->route('platform.pages.list')
                        ->permission('platform.systems.pages')
                ])
                ->divider()
                ->permission('platform.systems.posts'),

            Menu::make('E-Shop')
                ->icon('bs.cart2')
                ->list([
                    Menu::make('Statistiky')
                        ->icon('bs.graph-up')
                        ->route('platform.shop.statistics')
                        ->title("GROWTH"),
                    Menu::make('Produkty')
                        ->icon('bs.window-sidebar')
                        ->route('platform.shop.products')
                        ->permission('platform.systems.eshop.products')
                        ->title("PRODUKTY A PRODEJ"),
                    Menu::make('Kategorie')
                        ->icon('bs.window-sidebar')
                        ->route('platform.shop.categories')
                        ->permission('platform.systems.eshop.products'),
                    Menu::make('Prodej')
                        ->icon('bs.credit-card-2-front')
                        ->route('platform.shop.sales')
                        ->permission('platform.systems.eshop.sales'),
                    Menu::make('Poukázky')
                        ->icon('bs.card-list')
                        ->route('platform.shop.vouchers')
                        ->permission('platform.systems.eshop.vouchers'),
                    Menu::make('Dopravci')
                        ->icon('bs.box-seam')
                        ->route('platform.shop.carriers')
                        ->permission('platform.systems.eshop.carriers')
                        ->title("POUKÁZKY"),
                    Menu::make('Země')
                        ->icon('bs.globe')
                        ->route('platform.shop.countries.list')
                        ->permission('platform.eshop.countries'),
                    Menu::make('Objednávky')
                        ->icon('bs.box-seam')
                        ->route('platform.shop.orders')
                        ->badge(fn () => ShopOrders::where('status', 'PROCESSING')->count() ?: 0)
                        ->permission('platform.systems.eshop.orders')
                        ->slug('orders')
                        ->title("OBJEDNÁVKY A PODPORA"),
                    Menu::make('Vstupenky na podporu')
                        ->icon('bs.chat-right-text')
                        ->slug('tickets')
                        ->route('platform.shop.support')
                        ->badge(fn () => ShopSupportTickets::where('status', 'PENDING')->count() ?: 0)
                        ->permission('platform.systems.eshop.support'),
                    Menu::make('Nastavení')
                        ->icon('bs.gear')
                        ->route('platform.shop.settings')
                        ->permission('platform.systems.eshop.settings')
                        ->title("SPRÁVA PRODEJEN")
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
            ItemPermission::group("Systems")
                ->addPermission('platform.systems.roles', "Roles")
                ->addPermission('platform.systems.users', "Users")
                ->addPermission('platform.systems.settings', "Settings (Navbar)")
                ->addPermission('platform.audit.read', "Audit Logs (read)")
                ->addPermission('platform.systems.social', "Social Media Management (read/write)"),

            ItemPermission::group("Shop Management")
                ->addPermission('platform.systems.eshop', 'EShop (Navbar)')

                ->addPermission('platform.shop.categories.read', 'Categories (Read)')
                ->addPermission('platform.shop.categories.write', 'Categories (Write)')
                ->addPermission('platform.shop.orders.read', 'Orders (Read)')
                ->addPermission('platform.shop.products.read', 'Products (Read)')
                ->addPermission('platform.shop.products.write', 'Products (Write)')
                ->addPermission('platform.shop.sales.read', 'Sales (Read)')
                ->addPermission('platform.shop.sales.write', 'Sales (Write)')
                ->addPermission('platform.shop.settings.read', 'Settings (Read)')
                ->addPermission('platform.shop.settings.write', 'Settings (Write)')
                ->addPermission('platform.shop.statistics.read', 'Statistics (Read)')
                ->addPermission('platform.shop.statistics.write', 'Statistics (Write)')
                ->addPermission('platform.shop.support.read', 'Support (Read)')
                ->addPermission('platform.shop.support.write', 'Support (Write)')
                ->addPermission('platform.shop.vouchers.read', 'Vouchers (Read)')
                ->addPermission('platform.shop.vouchers.write', 'Vouchers (Write)')

                ->addPermission('platform.systems.eshop.settings', 'EShop Settings (read/write)')
                ->addPermission('platform.systems.eshop.support', 'EShop Support (read/write)')
                ->addPermission('platform.systems.eshop.orders', 'EShop Orders (read/write)')
                ->addPermission('platform.systems.eshop.products', 'EShop Products (read/write)')
                ->addPermission('platform.systems.eshop.vouchers', 'EShop Vouchers (write)')
                ->addPermission('platform.systems.eshop.sales', 'EShop Sales (read/write)')

                ->addPermission('platform.shop.carriers.read', 'EShop Carriers (Read)')
                ->addPermission('platform.shop.carriers.write', 'EShop Carriers (Write)')

                ->addPermission('platform.shop.countries.read', 'EShop Country (Read)')
                ->addPermission('platform.shop.countries.write', 'EShop Country (Write)')
                ->addPermission('platform.eshop.countries', 'EShop Country (Navbar)')

                ->addPermission('platform.systems.eshop.carriers', 'EShop Carriers (Navbar)'),

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

    private function isSalesEnabled(): bool
    {
        return ShopSettings::where('id', 1)->firstOrFail()->shop_sales == 1;
    }

    private function isVouchersEnabled(): bool
    {
        return ShopSettings::where('id', 1)->firstOrFail()->shop_sales == 1;
    }
}
