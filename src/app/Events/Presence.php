<?php

declare(strict_types=1);

namespace App\Events;
use App\Lib\PresenceBuilder;
use App\Models\User;
use App\Orchid\Presenters\UserPresenter;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Presence implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private UserPresenter $user;
    private PresenceBuilder $payload;

    public function __construct(User $user, PresenceBuilder $payload) {
        $this->user = new UserPresenter($user);
        $this->payload = $payload;
    }

    public function broadcastOn() {
        return new PresenceChannel('user');
    }

    public function broadcastWith()
    {
        return [
            'data' => array(
                'user' => [
                    'username' => $this->user->title(),
                    'avatarURL' => $this->user->image(),
                    'roles' => $this->user->subTitle(),
                ],
                'payload' => [
                    'slug' => $this->payload->slug,
                    'body' => $this->payload->payload
                ]
            )
        ];
    }
}
