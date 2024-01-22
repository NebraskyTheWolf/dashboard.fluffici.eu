<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Content;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Layout;

class ShopFeaturesSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
            CheckBox::make('shop-sales')
                ->title('Do you want the sales module on?'),
            CheckBox::make('shop-vouchers')
                ->title('Do you want the voucher module on?'),

            CheckBox::make('shop-billing')
                ->title('Do you want the billing module on?'),
            Input::make('billing-host')
                ->title('Please enter the provider host'),
            Password::make('billing-secret')
                ->title('Please enter your API secret.')
        ];
    }
}
