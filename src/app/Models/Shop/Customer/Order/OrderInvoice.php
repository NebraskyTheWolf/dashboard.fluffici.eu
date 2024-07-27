<?php

namespace App\Models\Shop\Customer\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class OrderInvoice extends Model
{
    use AsSource;

    public $table = 'order_invoice';
    public $connection = 'shop';

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrders::class);
    }

    public function orderIdentifier(): BelongsTo
    {
        return $this->belongsTo(OrderIdentifiers::class);
    }
}
