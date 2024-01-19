<?php

declare(strict_types=1);

namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Pages;
use App\Models\Events as DEvents;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;

class Statistics implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visits;
    public $tickets;
    public $orders;
    public $events;

    public function __construct()
    {
        $this->visits = number_format(intval(Pages::sum('visits')));
        $this->tickets = number_format(ShopSupportTickets::where('status', 'PENDING')->count());
        $this->orders = number_format(ShopOrders::where('status', 'PENDING')->count());
        $this->events = number_format(DEvents::where('status', 'INCOMING')->count());
    }

    public function broadcastOn()
    {
        return new Channel('statistics');
    }

    public function broadcastWith() {
        return [
            'data' => array(
                array(
                    'field' => 'visits',
                    'result' => $this->visits
                ),
                array(
                    'field' => 'tickets',
                    'result' => $this->tickets
                ),
                array(
                    'field' => 'orders',
                    'result' => $this->orders
                ),
                array(
                    'field' => 'events',
                    'result' => $this->events
                )
            )
        ];
    }
}
