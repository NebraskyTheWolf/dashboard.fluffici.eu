<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class PostsComments extends Model
{
    use AsSource, Chartable;

    public $connection = 'blog';
}
