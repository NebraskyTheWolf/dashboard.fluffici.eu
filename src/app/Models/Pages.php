<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Metrics\Chartable;
class Pages extends Model
{
    use AsSource, Chartable;

    /**
     * @var array
     */
    protected $fillable = [
        'page_slug',
        'title',
        'content'
    ];
}
