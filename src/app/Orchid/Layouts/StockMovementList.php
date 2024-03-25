<?php

namespace App\Orchid\Layouts;

use App\Models\OrderedProduct;
use Carbon\Carbon;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StockMovementList extends Table
{

    protected $target = 'movements';


    protected function columns(): iterable
    {
        return [
            TD::make('product_id', 'Product name')
                ->render(function (OrderedProduct $product) {
                    $products = $product->getProduct();
                    if ($products == null) {
                        return $product->product_id;
                    } else {
                        return $product->getProduct()->name;
                    }
                }),
            TD::make('quantity', 'Quantity'),
            TD::make('price', 'Quantity')
                ->render(function (OrderedProduct $product) {
                    return $product->price . ' Kc';
                }),
            TD::make("created_at", 'Created at')
                ->render(function (OrderedProduct $product) {
                    return Carbon::parse($product->created_at)->diffForHumans();
                })
        ];
    }
}
