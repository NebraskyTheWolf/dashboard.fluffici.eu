<?php

declare(strict_types=1);

namespace App\Events;
use App\Orchid\Presenters\UserPresenter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class UserUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $persona;

    public function __construct($userId)
    {
        $this->userId = $userId;

        $user = User::where('id', $userId)->firstOrFail();

        $this->persona = new UserPresenter($user);
    }

    public function broadcastOn()
    {
        return new Channel('user.'.$this->userId);
    }

    public function broadcastWith() {
        return [
            'data' => array(
                array(
                    'field' => 'persona-avatar',
                    'result' =>  $this->persona->image()
                ),
                array(
                    'field' => 'persona-subtitle',
                    'result' => $this->persona->subTitle()
                ),
                array(
                    'field' => 'persona-title',
                    'result' => $this->persona->title()
                )
            )
        ];
    }
}
