<?php

namespace App\Orchid\Screens\Shop;

use App\Orchid\Layouts\ShopCategoriesLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ShopCategories extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => \App\Models\ShopCategories::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categories';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.categories.read',
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
            Link::make("New")
                ->icon('bs.plus')
                //->route('platform.shop.categories.create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ShopCategoriesLayout::class
        ];
    }
}
