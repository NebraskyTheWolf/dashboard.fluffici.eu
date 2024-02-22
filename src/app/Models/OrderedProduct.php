<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Metrics\Chartable;

class OrderedProduct extends Model
{
    use HasFactory, Chartable;

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
}
