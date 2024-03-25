<?php

namespace App\Orchid\Layouts;

use App\Models\ProductInventory;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class InventoryList extends Table
{

    protected $target = 'inventories';

    protected function columns(): iterable
    {
        return [
            TD::make('product_id', 'Product')
                ->render(function (ProductInventory $inventory) {
                    $product = $inventory->getProduct();
                    if ($product == null) {
                        return Link::make($inventory->product_id)
                            ->icon('bs.pencil')
                            ->route('platform.inventory.edit', $inventory);
                    } else {
                        return Link::make($inventory->getProduct()->name)
                            ->icon('bs.pencil')
                            ->route('platform.inventory.edit', $inventory);
                    }
                }),
            TD::make('stock', 'Available stock')
                ->render(function (ProductInventory $products) {
                    $products = $products->getProduct();
                    if ($products == null) {
                        return '<a class="ui red label">Out of stock</a>';
                    } else {
                        $products->createOrGetInventory();
                        $available = $products->getAvailableProducts();

                        if ($available <= 0) {
                            return '<a class="ui red label">Out of stock</a>';
                        } else {
                            return $products->getAvailableProducts() . ' (pieces)';
                        }
                    }
                }),
            TD::make('upc_a', 'Inventory code')
                ->render(function (ProductInventory $inventory) {
                    $products = $inventory->getProduct();
                    if ($products == null) {
                        return '<a class="ui red label">Issue detected.</a>';
                    } else {
                        return $inventory->getProduct()->generateUPCA();
                    }
                })
        ];
    }
}
