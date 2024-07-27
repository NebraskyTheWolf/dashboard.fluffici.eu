<?php

namespace App\Models\Security\Auth;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class PasswordRecovery extends Model
{
    use AsSource;

    public $table = 'password_recovery';
}
