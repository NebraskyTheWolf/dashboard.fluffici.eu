<?php

namespace app\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class NewOrder extends Notification
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
            ->title('New customer order.')
            ->body("A new order has been placed.");
    }

    public function routeNotificationFor($notification): string
    {
        return 'dashboard';
    }
}
