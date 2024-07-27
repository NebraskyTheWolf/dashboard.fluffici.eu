<?php

namespace App\Models\Security\OAuth;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Scopes extends Model
{
    use AsSource;

    public $fillable = [
        'name',
        'description',
        'groupId',
        'parent'
    ];
}
