<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ShopOrders;

/**
 * Class OrderUpdateEvent
 *
 * Represents an event when an order is updated with a new status.
 */
class OrderUpdateEvent
{

    public $order;
    public $status;

    public function __construct(ShopOrders $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }
}
