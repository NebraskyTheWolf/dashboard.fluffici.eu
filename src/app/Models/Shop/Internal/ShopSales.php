<?php

namespace App\Models\Shop\Internal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class ShopSales extends Model
{
    use AsSource;
    public $connection = 'shop';
    protected $fillable = [
        'product_id',
        'product_type',
        'reduction',
        'deleted_at'
    ];

    public function getTotalSales()
    {
        return $this->sales->sum('amount');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(ShopProducts::class);
    }
}
