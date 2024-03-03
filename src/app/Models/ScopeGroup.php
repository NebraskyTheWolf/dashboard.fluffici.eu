<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
