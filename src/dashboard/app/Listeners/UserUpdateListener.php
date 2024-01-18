<?php

namespace App\Listeners;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserUpdate;
use App\Models\AuditLogs;

class UserUpdateListener 
{
    use InteractsWithQueue;

    public function handle(UserUpdate $event) {
        broadcast(new Channel('user.'.$event->userId));
    }
}
