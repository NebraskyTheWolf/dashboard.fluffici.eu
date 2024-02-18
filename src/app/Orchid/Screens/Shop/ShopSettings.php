<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Orchid\Layouts\Shop\ShopFeaturesSettings;
use App\Orchid\Layouts\Shop\ShopGeneralSettings;
use App\Orchid\Layouts\Shop\ShopMaintenanceSettings;
use App\Orchid\Layouts\Shop\ShopPaymentSettings;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Quill;
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
            'platform.shop.settings.write',
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
            Layout::rows([
                CheckBox::make('settings.enabled')
                    ->title("Is the shop active?"),

                Cropper::make('settings.favicon')
                    ->title('Upload the shop favicon.'),
                Cropper::make('settings.banner')
                    ->title('Upload the front-banner.'),

                Input::make('settings.email')
                    ->title('The public contact address.'),
                Quill::make('settings.return_policy')
                    ->title('Please write the Return Policy'),
            ])->title('General Settings'),

            Layout::rows([
                CheckBox::make('settings.shop_sales')
                    ->title('Do you want the sales module on?'),
                CheckBox::make('settings.shop_vouchers')
                    ->title('Do you want the voucher module on?'),

                CheckBox::make('settings.shop_billing')
                    ->title('Do you want the billing module on?'),
                Input::make('settings.billing-host')
                    ->title('Please enter the provider host'),
                Password::make('settings.billing-secret')
                    ->title('Please enter your API secret.')
            ])->title('Features Settings'),

            Layout::rows([
                CheckBox::make('settings.shop_maintenance')
                    ->title('Are you sure to take down the shop?'),
                Input::make('settings.shop_maintenance-text')
                    ->title('Please enter a description.')
            ])->title('Maintenance Settings')
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->settings['shop_maintenance-text'] = strip_tags($this->settings['shop_maintenance']);

        $this->settings->fill($request->get('settings'))->save();

        Toast::info('You edited the shop settings');

        event(new UpdateAudit('shop_settings', 'Updated the shop settings', Auth::user()->name));
    }
}
