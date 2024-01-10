<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Pages extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $fillable = [
        'page_slug',
        'title',
        'content'
    ];
}