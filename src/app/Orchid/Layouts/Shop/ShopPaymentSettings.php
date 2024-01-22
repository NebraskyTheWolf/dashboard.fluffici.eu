<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Post;
use App\Models\PostsLikes;
use App\Models\PostsComments;
use App\Models\User;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Content;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Facades\Layout;

class ShopPaymentSettings extends Rows
{
    protected function fields(): iterable
    {
        return [];
    }
}
