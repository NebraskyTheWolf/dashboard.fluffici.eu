<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PresenceEditor implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels ;


    public $slug;


    /**
     * Create a new event instance.
     */
    public function __construct($slug)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('editor.' . $this->slug),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => Auth::id(),
            'name' => Auth::user()->name,
            'slug' => $this->slug
        ];
    }
}
