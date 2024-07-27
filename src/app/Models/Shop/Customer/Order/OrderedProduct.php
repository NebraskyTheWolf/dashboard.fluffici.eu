<?php

namespace app\Models\Shop\Customer\Order;

use app\Models\Shop\Internal\ShopProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class OrderedProduct extends Model
{
    use AsSource, Chartable;

    public $connection = 'shop';
    protected $table = "ordered_product";

    /**
     * Retrieve the associated ShopProducts model for this instance.
     *
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ShopProducts::class);
    }

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
