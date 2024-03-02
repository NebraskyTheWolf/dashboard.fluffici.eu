<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class PasswordRecovery extends Model
{
    use AsSource;

    public $table = 'password_recovery';
}
