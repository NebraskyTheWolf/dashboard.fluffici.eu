<?php

declare(strict_types=1);

namespace App\Events;
use Illuminate\Queue\SerializesModels;

use App\Models\Pages;
use App\Models\Events as DEvents;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;

/**
 * Class Statistics
 *
 * Represents a set of statistics related to visits, support tickets, shop orders, and events.
 */
class Statistics
{
    use SerializesModels;

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
}
