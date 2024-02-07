<?php

declare(strict_types=1);

namespace App\Events;
use App\Orchid\Presenters\UserPresenter;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class UserUpdated
{
    use SerializesModels;

    public $userId;
    public $persona;

    public function __construct($userId)
    {
        $this->userId = $userId;

        $user = User::where('id', $userId)->firstOrFail();

        $this->persona = new UserPresenter($user);
    }
}
