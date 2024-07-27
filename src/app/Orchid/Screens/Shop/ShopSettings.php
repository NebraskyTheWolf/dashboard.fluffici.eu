<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Code;
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
     * Fetch the data to be displayed on the screen.
     *
     * @return iterable
     */
    public function query(): iterable
    {
        return [
            'settings' => \App\Models\Shop\Internal\ShopSettings::firstOrFail()
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

    /**
     * Define permissions for accessing this screen.
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.shop.settings.write',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return array
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.save')
                ->method('createOrUpdate')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                CheckBox::make('settings.enabled')
                    ->title('Is the shop active?')
                    ->sendTrueOrFalse(),

                Cropper::make('settings.favicon')
                    ->title('Upload the shop favicon'),
                Cropper::make('settings.banner')
                    ->title('Upload the front banner'),

                Input::make('settings.email')
                    ->title('Public contact address')
                    ->type('email')
                    ->required(),
                Code::make('settings.return_policy')
                    ->language('html')
                    ->title('Return Policy'),
            ])->title('General Settings'),

            Layout::rows([
                CheckBox::make('settings.shop_sales')
                    ->title('Enable the sales module?')
                    ->sendTrueOrFalse(),
                CheckBox::make('settings.shop_vouchers')
                    ->title('Enable the voucher module?')
                    ->sendTrueOrFalse(),

                CheckBox::make('settings.shop_billing')
                    ->title('Enable the billing module?')
                    ->sendTrueOrFalse(),

                Input::make('settings.billing_host')
                    ->title('Provider host')
                    ->type('url')
                    ->required(),
                Password::make('settings.billing_secret')
                    ->title('API secret')
                    ->required(),
            ])->title('Feature Settings'),

            Layout::rows([
                CheckBox::make('settings.shop_maintenance')
                    ->title('Enable maintenance mode?'),
                Quill::make('settings.shop_maintenance_text')
                    ->title('Maintenance description')
            ])->title('Maintenance Settings')
        ];
    }

    /**
     * Create or update the shop settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $data = $request->get('settings');
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'billing_host' => 'required|url',
            'billing_secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            Toast::error('Please correct the errors in the form.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['shop_maintenance_text'] = strip_tags($data['shop_maintenance_text']);
        $this->settings->fill($data)->save();

        Toast::info('Shop settings have been successfully updated.');

        event(new UpdateAudit('shop_settings', 'Updated the shop settings', Auth::user()->name));

        return redirect()->route('platform.shop.settings');
    }
}
