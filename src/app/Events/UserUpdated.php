<?php

declare(strict_types=1);

namespace App\Events;
use App\Orchid\Presenters\UserPresenters;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

/**
 * Class UserUpdated
 *
 * This class represents an event when a user is updated.
 */
class UserUpdated
{
    use SerializesModels;

    public $userId;
    public $persona;

    public function __construct($userId)
    {
        $this->userId = $userId;

        $user = User::where('id', $userId)->firstOrFail();

        $this->persona = new UserPresenters($user);
    }
}
