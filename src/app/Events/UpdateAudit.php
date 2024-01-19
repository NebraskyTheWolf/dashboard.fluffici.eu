<?php

declare(strict_types=1);

namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAudit implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $slug;
    public $type;
    public $username;

    public function __construct(string $slug, string $type, string $username)
    {
        $this->slug = $slug;
        $this->type = $type;
        $this->username = $username;
    }

    public function broadcastOn()
    {
        return new Channel('audit-update');
    }
}
