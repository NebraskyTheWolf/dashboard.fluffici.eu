<?php

namespace App\Orchid\Layouts\Shop;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Rows;

class ShopMaintenanceSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
            CheckBox::make('shop-maintenance')
                ->title('Are you sure to take down the shop?'),
            Quill::make('shop-maintenance-text')
                ->title('Please enter a description.')
        ];
    }
}
