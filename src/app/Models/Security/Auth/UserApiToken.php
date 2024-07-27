<?php

namespace App\Models\Security\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApiToken extends Model
{
    use HasFactory;

    public $table = 'user_api_token';

    function getUser(): \Orchid\Platform\Models\User
    {
        return \Orchid\Platform\Models\User::where('id', $this->user_id)->first();
    }
}
