<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopSupportTickets extends Model
{
    use AsSource;

    protected $fillable = [
        'status'
    ];

    protected $allowedFilters = [
        'order_id'         => Where::class,
        'status'         => Where::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'priority',
        'updated_at',
        'created_at',
    ];
}
