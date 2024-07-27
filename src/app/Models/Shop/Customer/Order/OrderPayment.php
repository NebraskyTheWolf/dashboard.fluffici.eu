<?php

namespace App\Models\Shop\Customer\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class OrderPayment extends Model
{
    use AsSource, Chartable, Filterable;

    public $connection = 'shop';

    protected $table = "order_payment";

    protected $fillable = [
        'order_id',
        'status',
        'transaction_id',
        'provider',
        'price'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrders::class);
    }

    public function orderIdentifier(): BelongsTo
    {
        return $this->belongsTo(OrderIdentifiers::class);
    }
}
