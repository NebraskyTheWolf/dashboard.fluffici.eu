<?php

namespace App\Listeners;

use App\Events\AkceUpdate;
use App\Mail\ReminderMail;
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
            if (env('APP_TEST_MAIL', false)) {
                Mail::to("vakea@fluffici.eu")->send(new ReminderMail($event->akce, User::where('email', 'vakea@fluffici.eu')));
            } else {
                $users = User::all();
                foreach ($users as $user) {
                    Mail::to($user)->send(new ReminderMail($event->akce, $user));
                }
            }
        }
    }
}
