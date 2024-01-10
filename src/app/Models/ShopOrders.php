<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopOrders extends Model
{
    use AsSource;

    protected $fillable = [
        'first_name',
        'last_name',
        'first_address',
        'second_address',
        'postal_code',
        'country',
        'email',
        'phone_number',
        'status',
        'tracking_number',
        'total_price'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'products'          => 'array'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'order_id'         => Where::class,
        'status'         => Where::class,
        'payment_method'         => Where::class,
        'first_name'       => Like::class,
        'last_name'       => Like::class,
        'email'      => Like::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'updated_at',
        'created_at',
    ];
}
