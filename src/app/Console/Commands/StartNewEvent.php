<?php

namespace App\Console\Commands;

use App\Events\AkceUpdate;
use App\Mail\ReminderMail;
use App\Models\Event\Events;
use App\Models\Subscriptions;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

class StartNewEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-new-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Handle method.
     *
     * This method is used to update the status of events and send reminder emails to subscribed users.
     *
     * @return void
     */
    public function handle(): void
    {
        $events = Events::all();
        foreach ($events as $event) {
            if (Carbon::parse($event->begin)->isPast()
                && ($event->status !== "ENDED")
                && ($event->status !== "CANCELLED")
                && ($event->status !== "FINISHED")) {

                $copy = $event;

                $event->update(
                    [
                        'status' => 'STARTED'
                    ]
                );

                event(new AkceUpdate($event, $copy));

                $subscriptions = Subscriptions::all();
                foreach ($subscriptions as $subscription) {
                    $user = User::where('id', $subscription->user_id);

                    if ($subscription->is_subscribed)
                        Mail::to($user->email)
                            ->send(new ReminderMail($event, $user));
                }
            }
        }
    }
}
