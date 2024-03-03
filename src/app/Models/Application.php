<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Application extends Model
{
    use AsSource;

    protected $table = 'application';

    public $fillable = [
        'clientId',
        'secret',
        'displayName',
        'role',
        'scope',
        'grants',
        'redirectUri',
    ];
}
