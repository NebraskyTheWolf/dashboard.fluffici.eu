<?php

namespace App\Console\Commands;

use App\Models\OrderedProduct;
use App\Models\ShopCategories;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use Illuminate\Console\Command;

class PurgeDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = ShopOrders::paginate();
        $products = ShopProducts::paginate();
        $orderProducts = OrderedProduct::paginate();
        $categories = ShopCategories::paginate();

        foreach ($orders as $order) {
            $order->delete();
        }

        foreach ($products as $prd) {
            $prd->delete();
        }

        foreach ($orderProducts as $prdele) {
            $prdele->delete();
        }

        foreach ($categories as $cat) {
            $cat->delete();
        }

        printf('All dummies was deleted. \n');
    }
}
