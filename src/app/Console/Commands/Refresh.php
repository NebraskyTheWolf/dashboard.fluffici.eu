<?php

namespace App\Console\Commands;

use App\Models\Shop\Customer\Order\ShopOrders;
use App\Models\Shop\Internal\ShopProducts;
use Illuminate\Console\Command;

class Refresh extends Command
{

    public $signature = "app:refresh";

    public $description = "Refreshing all status";

    public function handle() {
        $orders = ShopOrders::where('status', 'COMPLETED')->paginate();
        foreach ($orders as $order) {
            ShopOrders::updateOrCreate(
                [ 'id' => $order->id ],
                [
                    'status' => 'ARCHIVED'
                ]
            );
        }



        $products = ShopProducts::paginate();
    }
}
