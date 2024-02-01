<?php

namespace App\Orchid\Layouts\Shop;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ShopFeaturesSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
            CheckBox::make('settings.shop-sales')
                ->title('Do you want the sales module on?'),
            CheckBox::make('shop-vouchers')
                ->title('Do you want the voucher module on?'),

            CheckBox::make('settings.shop-billing')
                ->title('Do you want the billing module on?'),
            Input::make('settings.billing-host')
                ->title('Please enter the provider host'),
            Password::make('billing-secret')
                ->title('Please enter your API secret.')
        ];
    }
}
