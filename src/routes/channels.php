<?php

use Illuminate\Support\Facades\Broadcast;
use Orchid\Platform\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('presence-editor.{id}', function ($user, $slug) {
    return [
        'id' => $user->id,
        'name' => $user->name
    ];
});

Broadcast::channel('presence-countdown.bap', function ($user) {
    return [
        'id' => random_int(1, 200),
        'name' => \Ramsey\Uuid\Uuid::uuid4()
    ];
});
