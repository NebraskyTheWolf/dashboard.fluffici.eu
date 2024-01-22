<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class PostsComments extends Model
{
    use AsSource, Chartable;

    protected $allowedFilters = [
        'post_id'         => Where::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];
}
