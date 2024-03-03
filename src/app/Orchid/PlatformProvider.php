<?php
declare(strict_types=1);

namespace App\Orchid;

use App\Models\Events;
use App\Models\ShopCustomer;
use App\Models\ShopOrders;
use App\Models\ShopSettings;
use App\Models\ShopSupportTickets;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

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
     * @return array|Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Nastavení')
                ->icon('bs.gear')
                ->title('Navigace')
                ->list([
                    Menu::make('Sociální sítě')
                        ->icon('bs.person-walking')
                        ->route('platform.social.list')
                        ->permission('platform.systems.social'),
                    Menu::make('Zařízení')
                        ->badge(fn () => "Beta", Color::WARNING)
                        ->icon('bs.phone')
                        ->route('platform.device')
                        ->permission('platform.device'),
                    Menu::make("Uživatelé")
                        ->icon('bs.people')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.users')
                        ->title("Správa přístupu"),
                    Menu::make("Role a oprávnění")
                        ->icon('bs.shield')
                        ->route('platform.systems.roles')
                        ->permission('platform.systems.roles'),
                    Menu::make("Auditové protokoly")
                        ->icon('bs.clipboard2')
                        ->route('platform.audit')
                        ->permission('platform.audit.read'),
                ])
                ->divider()
                ->permission('platform.systems.settings'),

            Menu::make('OAuth')
                ->icon('bs.gear')
                ->badge(fn () => "Nové", Color::SECONDARY)
                ->title('Autentizace')
                ->list([
                    Menu::make('Aplikace')
                        ->icon('bs.boxes')
                        ->route('platform.application.list'),
                    Menu::make('Scope')
                        ->icon('bs.bounding-box-circles')
                        ->route('platform.scope.list')
                        ->permission('auth.scope.read'),
                    Menu::make('Scope Groups')
                        ->icon('bs.bookmark-star')
                        ->route('platform.scope_group.list')
                        ->permission('auth.scope_group.read'),
                ])->divider(),

            Menu::make('Přílohy')
                ->icon('bs.archive')
                ->list([
                    Menu::make('Soubory')
                        ->icon('bs.images')
                        ->route('platform.attachments')
                        ->permission('platform.systems.attachments.files'),
                    Menu::make("Zprávy & DMCA")
                        ->icon('bs.exclamation-octagon')
                        ->route('platform.reports')
                        ->permission('platform.systems.attachments.reports')
                ])
                ->divider()
                ->permission('platform.systems.attachments'),

            Menu::make('Hlavní stránka')
                ->icon('bs.chat-right-text')
                ->list([
                    Menu::make('Zprávy')
                        ->icon('bs.book')
                        ->route('platform.post.list')
                        ->permission('platform.systems.posts'),
                    Menu::make('Odeslat e-mail')
                        ->icon('bs.envelope')
                        ->route('platform.admin.sendmail')
                        ->permission('platform.systems.email')
                        ->badge(fn () => "Nové", Color::SECONDARY),
                    Menu::make('Události')
                        ->icon('bs.calendar-event')
                        ->route('platform.events.list')
                        ->slug('events')
                        ->badge(fn () => Events::where('status', 'INCOMING')->count() ?: 0)
                        ->permission('platform.systems.events'),
                    Menu::make('Stránky')
                        ->icon('bs.file-earmark')
                        ->route('platform.pages.list')
                        ->permission('platform.systems.pages')
                ])
                ->divider()
                ->permission('platform.systems.posts'),

            Menu::make('Účetnictví')
                ->icon('bs.calculator')
                ->list([
                    Menu::make('Domů')
                        ->icon('bs.house')
                        ->route('platform.accounting.main'),

                    Menu::make('Měsíční zpráva')
                        ->icon('bs.briefcase')
                        ->route('platform.shop.reports'),

                    Menu::make('Zpráva o transakcích')
                        ->icon('bs.graph-up')
                        ->route('platform.accounting.transactions.reports'),

                    Menu::make('Účetní výkaz')
                        ->icon('bs.buildings')
                        ->route('platform.accounting.reports'),

                    Menu::make('Transakce')
                        ->icon('bs.arrow-left-right')
                        ->route('platform.accounting.transactions'),

                    Menu::make('Faktury')
                        ->icon('bs.card-checklist')
                        ->route('platform.accounting.invoices'),
                ])
                ->divider()
                ->badge(fn () => "Nové", Color::SECONDARY)
                ->permission('platform.accounting.navbar'),

            Menu::make('E-Shop')
                ->icon('bs.cart2')
                ->list([
                    Menu::make('Statistika')
                        ->icon('bs.graph-up')
                        ->route('platform.shop.statistics')
                        ->title("RŮST"),
                    Menu::make('Daně')
                        ->icon('bs.exposure')
                        ->route('tax.group.list')
                        ->permission('platform.shop.taxes.navbar'),
                    Menu::make('Zákazníci')
                        ->icon('bs.card-list')
                        ->route('platform.shop.customers')
                        ->badge(fn () => ShopCustomer::count() ?: 0),
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
                        ->permission('platform.systems.eshop.sales')
                        ->canSee($this->isSalesEnabled()),
                    Menu::make('Poukazy')
                        ->icon('bs.card-list')
                        ->route('platform.shop.vouchers')
                        ->permission('platform.systems.eshop.vouchers')
                        ->canSee($this->isVouchersEnabled()),
                    Menu::make('Přepravci')
                        ->icon('bs.box-seam')
                        ->route('platform.shop.carriers')
                        ->permission('platform.systems.eshop.carriers')
                        ->title("POUKAZY"),
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
                    Menu::make('Podpora - lístky')
                        ->icon('bs.chat-right-text')
                        ->slug('tickets')
                        ->route('platform.shop.support')
                        ->badge(fn () => ShopSupportTickets::where('status', 'PENDING')->count() ?: 0)
                        ->permission('platform.systems.eshop.support'),
                    Menu::make('Nastavení')
                        ->icon('bs.gear')
                        ->route('platform.shop.settings')
                        ->permission('platform.systems.eshop.settings')
                        ->title("ŘÍZENÍ OBCHODU")
                ])
                ->divider()
                ->permission('platform.systems.eshop')
        ];
    }

    public function permissions(): array
    {
        return [
            ItemPermission::group("Systémy")
                ->addPermission('platform.systems.roles', "Role")
                ->addPermission('platform.systems.users', "Uživatelé")
                ->addPermission('platform.systems.settings', "Nastavení (Navbar)")
                ->addPermission('platform.audit.read', "Auditové protokoly (čtení)")
                ->addPermission('platform.systems.social', "Správa sociálních médií (čtení/zápis)")
                ->addPermission('platform.systems.dashboard', "Dashboard access (login)"),

            ItemPermission::group("Správa obchodu")
                ->addPermission('platform.systems.eshop', 'EObchod (Navbar)')

                ->addPermission('platform.shop.categories.read', 'Kategorie (Čtení)')
                ->addPermission('platform.shop.categories.write', 'Kategorie (Zápis)')
                ->addPermission('platform.shop.orders.read', 'Objednávky (Čtení)')
                ->addPermission('platform.shop.orders.write', 'Objednávky (Zápis)')
                ->addPermission('platform.shop.products.read', 'Produkty (Čtení)')
                ->addPermission('platform.shop.products.write', 'Produkty (Zápis)')
                ->addPermission('platform.shop.sales.read', 'Prodej (Čtení)')
                ->addPermission('platform.shop.sales.write', 'Prodej (Zápis)')
                ->addPermission('platform.shop.settings.read', 'Nastavení (Čtení)')
                ->addPermission('platform.shop.settings.write', 'Nastavení (Zápis)')
                ->addPermission('platform.shop.statistics.read', 'Statistika (Čtení)')
                ->addPermission('platform.shop.statistics.write', 'Statistika (Zápis)')
                ->addPermission('platform.shop.support.read', 'Podpora (Čtení)')
                ->addPermission('platform.shop.support.write', 'Podpora (Zápis)')
                ->addPermission('platform.shop.vouchers.read', 'Poukazy (Čtení)')
                ->addPermission('platform.shop.vouchers.write', 'Poukazy (Zápis)')

                ->addPermission('platform.shop.taxes.navbar', 'Daň (Navbar)')
                ->addPermission('platform.shop.taxes.write', 'Daň (Zápis)')
                ->addPermission('platform.shop.taxes.read', 'Daň (Čtení)')

                ->addPermission('platform.systems.eshop.settings', 'Nastavení EObchodu (čtení/zápis)')
                ->addPermission('platform.systems.eshop.support', 'Podpora EObchodu (čtení/zápis)')
                ->addPermission('platform.systems.eshop.orders', 'Objednávky EObchodu (čtení/zápis)')
                ->addPermission('platform.systems.eshop.products', 'Produkty EObchodu (čtení/zápis)')
                ->addPermission('platform.systems.eshop.vouchers', 'Poukazy EObchodu (zápis)')
                ->addPermission('platform.systems.eshop.sales', 'Prodej EObchodu (čtení/zápis)')

                ->addPermission('platform.shop.carriers.read', 'Přepravci EObchodu (Čtení)')
                ->addPermission('platform.shop.carriers.write', 'Přepravci EObchodu (Zápis)')

                ->addPermission('platform.shop.countries.read', 'Země EObchodu (Čtení)')
                ->addPermission('platform.shop.countries.write', 'Země EObchodu (Zápis)')
                ->addPermission('platform.eshop.countries', 'Země EObchodu (Navbar)')

                ->addPermission('platform.systems.eshop.carriers', 'Přepravci EObchodu (Navbar)'),

            ItemPermission::group('Přílohy')
                ->addPermission('platform.systems.attachments.files', 'Soubory (Čtení)')
                ->addPermission('platform.systems.attachments.files.write', 'Soubory (Zápis)')
                ->addPermission('platform.systems.attachments.reports', 'Zprávy (Čtení)')
                ->addPermission('platform.systems.attachments.reports.write', 'Zprávy (Zápis)')
                ->addPermission('platform.systems.attachments', 'Přílohy (Navbar)'),

            ItemPermission::group('Účetnictví')
                ->addPermission('platform.accounting.monthly_report', 'Měsíční zprávy (Čtení / Zápis)')
                ->addPermission('platform.accounting', 'Hlavní (Čtení)')
                ->addPermission('platform.accounting.navbar', 'Přístup (Navbar)')
                ->addPermission('platform.accounting.invoices', 'Faktury (Čtení / Zápis)')
                ->addPermission('platform.accounting.transactions', 'Transakce (Čtení / Zápis)'),

            ItemPermission::group('Správa zařízení')
                ->addPermission("platform.device", "Zařízení (Přístup)"),

            ItemPermission::group("Správa stránek a událostí")
                ->addPermission('platform.systems.posts', "Příspěvky (Navbar)")
                ->addPermission('platform.systems.events', "Události (Navbar)")
                ->addPermission('platform.systems.pages', "Stránky (Navbar)")
                ->addPermission('platform.systems.gallery', "Fotografie (Navbar)")

                ->addPermission('platform.systems.pages.read', "Stránky (čtení)")
                ->addPermission('platform.systems.pages.write', "Stránky (zápis)")

                ->addPermission('platform.systems.events.read', "Události (čtení)")
                ->addPermission('platform.systems.events.write', "Události (zápis)")

                ->addPermission('platform.systems.post.read', "Příspěvky (čtení)")
                ->addPermission('platform.systems.post.write', "Příspěvky (zápis)")
                ->addPermission('platform.systems.email', "Odeslat e-mail"),

            ItemPermission::group("Firebase Messaging")
                ->addPermission('platform.firebase.token.read', 'Token (Čtení)')
                ->addPermission('platform.firebase.token.write', 'Token (Zápis)')
                ->addPermission('platform.firebase.subscribe', 'Odběr (Čtení/Přítomnost)')
                ->addPermission('platform.firebase.notification.ack', 'Oznámení (ACK)'),

            ItemPermission::group('Kalendář & Agenda')
                ->addPermission('api.calendar.add', 'Přidat událost (API)')
                ->addPermission('api.calendar.update', 'Aktualizovat událost (API)')
                ->addPermission('api.calendar.remove', 'Odstranit událost (API)'),

            ItemPermission::group('OAuth platform')
                ->addPermission('auth.scope.read', 'Scope (Read)')
                ->addPermission('auth.scope.write', 'Scope (Write)')

                ->addPermission('auth.scope_group.read', 'Scope Group (Read)')
                ->addPermission('auth.scope_group.write', 'Scope Group (Write)')

                ->addPermission('auth.application.read', 'Application (Read)')
                ->addPermission('auth.application.write', 'Application (Write)')
        ];
    }
    private function isSalesEnabled(): bool
    {
        return ShopSettings::latest()->first()->shop_sales;
    }

    private function isVouchersEnabled(): bool
    {
        return ShopSettings::latest()->first()->shop_vouchers;
    }
}
