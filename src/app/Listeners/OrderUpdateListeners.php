<?php

namespace App\Listeners;
use App\Events\OrderUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
class OrderUpdateListeners
{
    use InteractsWithQueue;

    public function handle(OrderUpdateEvent $event) {
        // TODO: Send email
    }
}
