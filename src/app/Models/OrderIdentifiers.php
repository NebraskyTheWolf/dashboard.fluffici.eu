<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIdentifiers extends Model
{
    use HasFactory;

    public $table = 'order_public_identifiers';

    /**
     * Fetches the order identified by the given order ID.
     *
     * @param string $orderId The ID of the order to fetch
     * @return OrderIdentifiers The order identifiers of the fetched order
     */
    public function fetchOrder(string $orderId): OrderIdentifiers
    {
        return $this->where('order_id', $orderId)->first();
    }
}
