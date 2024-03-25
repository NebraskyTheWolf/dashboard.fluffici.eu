<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ProductInventory extends Model
{
    use AsSource;
    public $connection = 'shop';
    public $table = 'product_inventory';

    public $fillable = [
        'product_id',
        'available'
    ];

    /**
     * @throws \Exception
     */
    public function getProduct(): ?ShopProducts
    {
        $product = ShopProducts::where('id', $this->product_id);

        if ($product->exists()) {
            return $product->first();
        } else {
            $this->delete();
        }

        return null;
    }
}
