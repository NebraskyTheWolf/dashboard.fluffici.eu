<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Content;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Layout;

class ShopMaintenanceSettings extends Rows
{
    protected function fields(): iterable
    {
        return [
            CheckBox::make('settings.shop-maintenance')
                ->title('Are you sure to take down the shop?'),
            Quill::make('settings.shop-maintenance-text')
                ->title('Please enter a description.')
        ];
    }
}
