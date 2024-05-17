<?php

namespace App\Listeners;

use App\Events\AkceUpdate;
use App\Mail\ReminderMail;
use App\Mail\ScheduleMail;
use App\Models\Subscriptions;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Broadcasting\PendingBroadcast;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;
use Pusher\Pusher;
use Pusher\PusherException;

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
     * @throws PusherException
     * @throws GuzzleException
     */
    public function handle(AkceUpdate $event): void {
        $pusher = new Pusher(
            env('AKCE_PUSHER_APP_KEY'),
            env('AKCE_PUSHER_APP_SECRET'),
            env('AKCE_PUSHER_APP_ID'),
            [
                'cluster' => env('AKCE_PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        if ($event->akce->exists) {
            $pusher->trigger('notifications-event', 'update-trello', [
                'event' => $event->akce->event_id,
                'status' => strtolower($event->akce->status)
            ]);
        } else {
            $start = Carbon::parse($event->begin);
            $startAt = $start->isoFormat('MMMM D, YYYY');
            $startAtTime = $start->isoFormat('HH:mm');

            $pusher->trigger('notifications-event', 'create-trello', [
                'event' => $event->akce->event_id,
                'thumbnail' => ($event->thumbnail_id != null ? "https://autumn.fluffici.eu/attachments/" . $event->thumbnail_id . "?width=600&height=300" : 'none'),
                'name' => $event->akce->name,
                'description' => $event->akce->descriptions,
                'time' => 'Datum: ' . $startAt . ' Čas: ' . $startAtTime
            ]);
        }
    }
}
