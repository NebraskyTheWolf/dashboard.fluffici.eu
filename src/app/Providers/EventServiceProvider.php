<?php

namespace App\Providers;

use App\Events\OrderUpdateEvent;
use App\Listeners\OrderUpdateListeners;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Listeners\AuditListener;
use App\Events\UpdateAudit;

use App\Events\Statistics;
use App\Listeners\StatisticsListener;

use App\Events\UserUpdated;
use App\Listeners\UserUpdateListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UpdateAudit::class => [
            AuditListener::class,
        ],
        Statistics::class => [
            StatisticsListener::class,
        ],
        UserUpdated::class => [
            UserUpdateListener::class,
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
