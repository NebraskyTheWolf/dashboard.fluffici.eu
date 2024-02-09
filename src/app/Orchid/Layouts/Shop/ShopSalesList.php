<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\ShopProducts;
use App\Models\ShopSales;
use Carbon\Carbon;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopSalesList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sales';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('product', 'Action')
                ->render(function (ShopSales $sale) {
                    $product = ShopProducts::where('id', $sale->product_id);
                    if ($product->exists()) {
                        return DropDown::make('Click here')
                            ->icon('bs.caret-down')
                            ->list([
                                Link::make('Edit Product')
                                    ->icon('bs.box')
                                    ->route('platform.shop.products.edit', $product->firstOrFail()),
                                Link::make('Edit Sale')
                                    ->icon('bs.cash-coin')
                                    ->route('platform.shop.sales.edit',$sale),
                            ]);
                    } else {
                        return Link::make('Edit')
                            ->icon('bs.pencil')
                            ->route('platform.shop.sales.edit',$sale);
                    }
                }),
            TD::make('reduction', 'Reduction')
                ->render(function (ShopSales $sale) {
                    return $sale->reduction . '%';
                }),
            TD::make('deleted_at', 'Expiration')
                ->render(function (ShopSales $sale) {
                    if ($sale->deleted_at === null) {
                        return 'No expiration';
                    } else {
                        return Carbon::parse($sale->deleted_at)->diffForHumans();
                    }
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.plus-slash-minus';
    }

    protected function textNotFound(): string
    {
        return 'No sale available.';
    }
}
