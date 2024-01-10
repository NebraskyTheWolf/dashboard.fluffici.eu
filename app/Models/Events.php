<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'descriptions',
        'begin',
        'end',
        'status',
        'type',

        'min',
        'max',

        'city',
        'link'
    ];

    protected $casts = [
        'min'  => 'array',
        'max'  => 'array'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'event_id'         => Where::class,
        'status'         => Where::class,
        'city'       => Like::class,
        'begin' => WhereDateStartEnd::class,
        'end' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'event_id',
        'name',
        'status',
        'type',
        'begin',
        'end',
        'updated_at',
        'created_at'
    ];
}
