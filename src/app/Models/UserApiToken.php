<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApiToken extends Model
{
    use HasFactory;

    function getUser(): \Orchid\Platform\Models\User
    {
        return \Orchid\Platform\Models\User::where('id', $this->user_id);
    }
}
