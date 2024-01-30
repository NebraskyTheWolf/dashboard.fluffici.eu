<?php

namespace App\Orchid\Layouts\Shop;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Rows;

class ShopGeneralSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
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
        ];
    }
}
