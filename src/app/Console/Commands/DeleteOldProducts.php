<?php

namespace App\Console\Commands;

use App\Models\ShopProducts;
use App\Models\ShopSales;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-products';

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
        $products = ShopProducts::paginate();
        foreach ($products as $product) {
            if ($product->deleted_at !== null) {
                if (Carbon::parse($product->deleted_at)->isPast()) {
                    $product->update(
                        [
                            'displayed' => false,
                            'deleted_at' => null
                        ]
                    );
                }
            }
        }
    }
}
