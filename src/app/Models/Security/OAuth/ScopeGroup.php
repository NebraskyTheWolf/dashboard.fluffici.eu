<?php

namespace App\Models\Security\OAuth;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ScopeGroup extends Model
{
    use AsSource;

    public $fillable = [
        'name',
        'description'
    ];

    public $table = 'scope_group';
}
