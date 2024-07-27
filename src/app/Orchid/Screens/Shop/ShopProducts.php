<?php

namespace App\Orchid\Screens\Shop;

use App\Orchid\Layouts\Shop\ShopProductsList;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class ShopProducts extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'products' => \App\Models\Shop\Internal\ShopProducts::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('products.screen.title');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.products.read',
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
            Link::make('Create product')
                ->icon('bs.plus-circle')
                ->href(route('platform.shop.products.edit')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ShopProductsList::class
        ];
    }
}
