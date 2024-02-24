<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

/**
 * Class ShopReportReady
 *
 * Represents a notification that a shop report is ready.
 */
class ShopReportReady extends Notification
{

    public function via(object $notifiable): array
    {
        return [PusherChannel::class];
    }

    /**
     * Pushes a notification using PusherMessage.
     *
     * @param mixed $notifiable The recipient of the notification.
     * @return PusherMessage The created PusherMessage instance.
     */
    public function toPushNotification($notifiable)
    {
        return PusherMessage::create()
            ->web()
            ->sound('success')
            ->link(env('PUBLIC_URL'))
            ->title('New monthly reports')
            ->body("The monthly report has been created and available.");
    }

    public function routeNotificationFor($notification): string
    {
        return 'dashboard';
    }
}
