<?php

namespace App\Orchid\Layouts\Shop;

use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class ShopCarriersSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
            Cropper::make('carrier-icon')
                ->title('Upload the carrier icon.'),
            Input::make('carrier-name')
                ->title('Please enter the name of the primary carrier.')
                ->type("text"),
            Input::make('carrier-fee')
                ->title('Please enter the average price of the delivery fee.')
                ->type("number")
        ];
    }
}
