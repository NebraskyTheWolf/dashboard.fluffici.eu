<?php

namespace App\Models\Security\Account;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class UserScopes extends Model
{
    use AsSource;

    protected $table = 'user_scopes';
}
