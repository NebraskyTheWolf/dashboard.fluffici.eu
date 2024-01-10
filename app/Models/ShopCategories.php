<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopCategories extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'order',
        'displayed',
        'deleted_at'
    ];
}
