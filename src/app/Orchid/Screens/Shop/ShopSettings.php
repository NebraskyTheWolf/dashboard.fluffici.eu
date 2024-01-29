<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Orchid\Layouts\Shop\ShopFeaturesSettings;
use App\Orchid\Layouts\Shop\ShopGeneralSettings;
use App\Orchid\Layouts\Shop\ShopMaintenanceSettings;
use App\Orchid\Layouts\Shop\ShopPaymentSettings;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ShopSettings extends Screen
{
    public $settings;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
            'settings' => \App\Models\ShopSettings::where('id', 1)->firstOrFail()
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
                'Features' => ShopFeaturesSettings::class,
                'Maintenance' => ShopMaintenanceSettings::class
            ])->activeTab('General')
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->settings->fill($request->get('settings'))->save();

        Toast::info('You edited the shop settings');

        event(new UpdateAudit('shop_settings', 'Updated the shop settings', Auth::user()->name));
    }
}
