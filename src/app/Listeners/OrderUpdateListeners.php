<?php

namespace App\Listeners;
use App\Events\OrderUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
class OrderUpdateListeners
{
    use InteractsWithQueue;

    /**
     * Handles the OrderUpdateEvent and sends an email.
     *
     * @param OrderUpdateEvent $event The event object containing the order update details.
     * @return void
     */
    public function handle(OrderUpdateEvent $event) {
        // TODO: Send email
    }
}
