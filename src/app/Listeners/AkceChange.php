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

        $start = Carbon::parse($event->akce->begin);
        $startAt = $start->isoFormat('D. MMMM YYYY');
        $startAtTime = $start->isoFormat('HH:mm');

        $startPrev = Carbon::parse($event->previous->begin);
        $startAtPrev = $startPrev->isoFormat('D. MMMM YYYY');
        $startAtTimePrev = $startPrev->isoFormat('HH:mm');

        if ($event->akce->exists) {
            $pusher->trigger('notifications-event', 'update-trello', [
                'event' => $event->akce->event_id,
                'type' => $event->akce->type,
                'status' => strtolower($event->akce->status),
                'current' => [
                    'thumbnail' => ($event->akce->thumbnail_id != null ? "https://autumn.fluffici.eu/attachments/" . $event->akce->thumbnail_id . "?width=600&height=300" : 'none'),
                    'name' => $event->akce->name,
                    'description' => $event->akce->descriptions,
                    'time' => 'Datum: ' . $startAt . ' Čas: ' . $startAtTime,
                ],
                'previous' => [
                    'thumbnail' => ($event->previous->thumbnail_id != null ? "https://autumn.fluffici.eu/attachments/" . $event->previous->thumbnail_id . "?width=600&height=300" : 'none'),
                    'name' => $event->previous->name,
                    'description' => $event->previous->descriptions,
                    'time' => 'Datum: ' . $startAtPrev . ' Čas: ' . $startAtTimePrev,
                ]
            ]);
        } else {
            $pusher->trigger('notifications-event', 'create-trello', [
                'event' => $event->akce->event_id,
                'type' => $event->akce->type,
                'thumbnail' => ($event->akce->thumbnail_id != null ? "https://autumn.fluffici.eu/attachments/" . $event->akce->thumbnail_id . "?width=600&height=300" : 'none'),
                'name' => $event->akce->name,
                'description' => $event->akce->descriptions,
                'time' => 'Datum: ' . $startAt . ' Čas: ' . $startAtTime
            ]);
        }
    }
}
