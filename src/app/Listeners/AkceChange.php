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
            $this->handleEvent($event->akce);
            $pusher->trigger('notifications-event', 'create-trello', [
                'event' => $event->akce->event_id,
                'thumbnail' => $event->akce->thumbnail,
                'name' => $event->akce->name,
                'description' => $event->akce->description,
                'time' => 'Datum: ' . $event->akce->startAt . ' Čas: ' . $event->akce->startAtTime
            ]);
        }
    }

    function handleEvent(object $event): void {
        if ($event->thumbnail === null && $event->thumbnail_id != null) {
            $event->thumbnail = "https://autumn.fluffici.eu/attachments/{$event->thumbnail_id}?width=600&height=300";
        } else if ($event->thumbnail === null) {
            $event->thumbnail = "none";
        }

        if ($event->startAt === null && $event->begin != null) {
            $start = Carbon::parse($event->begin);
            $event->startAt = $start->isoFormat('MMMM D, YYYY');
            $event->startAtTime = $start->isoFormat('HH:mm');
        }
    }
}
