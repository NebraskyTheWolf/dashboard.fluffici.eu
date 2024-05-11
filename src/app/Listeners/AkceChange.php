<?php

namespace App\Listeners;

use App\Events\AkceUpdate;
use App\Mail\ReminderMail;
use App\Mail\ScheduleMail;
use App\Models\Subscriptions;
use Illuminate\Broadcasting\PendingBroadcast;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

class AkceChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AkceUpdate $event): void
    {
        if ($event->akce->status === "STARTED") {

        }
    }
}
