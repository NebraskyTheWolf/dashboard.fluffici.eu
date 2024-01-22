<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Pages;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;
use App\Orchid\Layouts\Shop\ShopCarriersSettings;
use App\Orchid\Layouts\Shop\ShopFeaturesSettings;
use App\Orchid\Layouts\Shop\ShopGeneralSettings;
use App\Orchid\Layouts\Shop\ShopMaintenanceSettings;
use App\Orchid\Layouts\Shop\ShopPaymentSettings;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ShopSettings extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
            'settings' => \App\Models\ShopSettings::where('id', 1)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Settings';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.categories.write',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.save')
                ->method('createOrUpdate')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'General' => ShopGeneralSettings::class,
                'Payments Methods' => ShopPaymentSettings::class,
                'Carrier' => ShopCarriersSettings::class,
                'Features' => ShopFeaturesSettings::class,
                'Maintenance' => ShopMaintenanceSettings::class
            ])->activeTab('General')
        ];
    }
}
