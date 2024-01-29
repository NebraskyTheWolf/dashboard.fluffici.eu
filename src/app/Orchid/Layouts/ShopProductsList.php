<?php

namespace App\Orchid\Layouts;

use App\Models\ShopCategories;
use App\Models\ShopProducts;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopProductsList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('products.table.name')),
            TD::make('category', __('products.table.category'))
                ->render(function (ShopProducts $product) {
                    $category = ShopCategories::where('id', $product->category_id);

                    if ($category->exists()) {
                        return $category->firstOrFail()->name;
                    } else {
                        return __('products.table.category.uncategorized');
                    }
                }),
            TD::make('price', __('products.table.price'))
                ->render(function (ShopProducts $product) {
                    return $product->price . ' Kc';
                })
        ];
    }
}
