<?php

namespace App\Providers;

use App\Events\AkceUpdate;
use App\Events\OrderUpdateEvent;
use App\Events\PresenceEditor;
use App\Listeners\AkceChange;
use App\Listeners\LockUserForLogin;
use App\Listeners\OrderUpdateListeners;
use App\Listeners\PresenceEditorListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Listeners\AuditListener;
use App\Events\UpdateAudit;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array $listen
     * This array represents a mapping of event classes to their corresponding listeners.
     * Each event class is associated with an array of listener classes that should handle that event.
     * The format of the array is as follows:
     *
     * [
     *     EventClass1::class => [
     *         ListenerClass1::class,
     *         ListenerClass2::class,
     *         ...
     *     ],
     *     EventClass2::class => [
     *         ListenerClass3::class,
     *         ListenerClass4::class,
     *         ...
     *     ],
     *     ...
     * ]
     *
     * @see UpdateAudit
     * @see AuditListener
     * @see OrderUpdateEvent
     * @see OrderUpdateListeners
     * @see AkceUpdate
     * @see AkceChange
     */
    protected $listen = [
        UpdateAudit::class => [
            AuditListener::class,
        ],
        OrderUpdateEvent::class => [
            OrderUpdateListeners::class
        ],
        AkceUpdate::class => [
            AkceChange::class
        ],
        Login::class => [
            LockUserForLogin::class,
        ],
        PresenceEditor::class => [
            PresenceEditorListener::class
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
