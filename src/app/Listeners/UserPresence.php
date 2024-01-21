<?php

namespace App\Listeners;

use App\Events\Presence;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class UserPresence
{
    use InteractsWithQueue;

    public function handle(Presence $event) {
        broadcast('presence.user.1');
    }
}
