<?php

namespace App\Models\Shop\Customer\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderIdentifiers extends Model
{
    use HasFactory;

    public $connection = 'shop';

    public $table = 'order_public_identifiers';

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrders::class);
    }
}
