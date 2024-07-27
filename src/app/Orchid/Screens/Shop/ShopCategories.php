<?php

namespace App\Orchid\Screens\Shop;

use App\Orchid\Layouts\Shop\ShopCategoriesLayout;
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
            'categories' => \App\Models\Shop\Internal\ShopCategories::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('category.screen.title');
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
            Link::make(__('category.screen.button.new'))
                ->icon('bs.plus')
                ->href(route('platform.shop.categories.edit')),
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
