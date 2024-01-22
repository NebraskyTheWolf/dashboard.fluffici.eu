<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Content;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Layout;

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
