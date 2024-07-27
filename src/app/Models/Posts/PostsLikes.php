<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class PostsLikes extends Model
{
    use AsSource, Chartable;
}
