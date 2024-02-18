<?php

namespace App\Models;

use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class PostsLikes extends Model
{
    use AsSource, Chartable;

    public $connection = 'blog';
}
