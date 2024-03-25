<?php

namespace App\Orchid\Screens\Shop;

use App\Models\OrderedProduct;
use App\Models\ProductInventory;
use App\Orchid\Layouts\InventoryList;
use App\Orchid\Layouts\StockMovementList;
use Orchid\Screen\Screen;

class InventoryScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'inventories' => ProductInventory::paginate(),
            'movements' => OrderedProduct::paginate()
        ];
    }


    public function name(): ?string
    {
        return 'Inventory';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.eshop.inventory.read'
        ];
    }


    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            InventoryList::class,
            StockMovementList::class
        ];
    }
}
