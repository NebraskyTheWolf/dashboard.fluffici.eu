<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\ShopCategories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopCategoriesLayout extends Table
{
    public $target = 'categories';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('category.table.name'))
                ->render(function (ShopCategories $categories) {
                    return Link::make($categories->name)
                        ->icon('bs.box-arrow-in-right')
                        ->href(route('platform.shop.categories.edit', $categories));
                }),
            TD::make('order', __('category.table.position')),
            TD::make('displayed', __('category.table.displayed'))
                ->render(function (ShopCategories $categories) {
                     if ($categories->displayed === 1) {
                         return 'Yes';
                     } else {
                         return 'No';
                     }
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.bounding-box';
    }

    protected function textNotFound(): string
    {
        return 'No categories available.';
    }
}
