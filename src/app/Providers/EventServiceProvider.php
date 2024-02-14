<?php

namespace App\Providers;

use App\Events\OrderUpdateEvent;
use App\Listeners\OrderUpdateListeners;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Listeners\AuditListener;
use App\Events\UpdateAudit;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array $listen An associative array that maps event classes to an array of listener classes.
     *
     * The key in the array represents the event class, and the value represents an array of listener classes
     * that are interested in handling that event.
     *
     * Example usage:
     * $listen = [
     *     UpdateAudit::class => [
     *         AuditListener::class,
     *     ],
     *     OrderUpdateEvent::class => [
     *         OrderUpdateListeners::class
     *     ]
     * ];
     *
     * In the example above, the event class UpdateAudit is mapped to a single listener class AuditListener.
     * Similarly, the event class OrderUpdateEvent is mapped to a single listener class OrderUpdateListeners.
     *
     * @see UpdateAudit       The event class that triggers the AuditListener.
     * @see AuditListener     The listener class that handles the AuditEvent.
     * @see OrderUpdateEvent  The event class that triggers the OrderUpdateListeners.
     * @see OrderUpdateListeners The listener class that handles the OrderUpdateEvent.
     */
    protected $listen = [
        UpdateAudit::class => [
            AuditListener::class,
        ],
        OrderUpdateEvent::class => [
            OrderUpdateListeners::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void { }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool {
        return false;
    }
}
