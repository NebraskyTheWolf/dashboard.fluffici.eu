<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class OrderPayment extends Model
{
    use AsSource, Chartable, Filterable;

    public $connection = 'shop';

    protected $table = "order_payment";

    protected $fillable = [
        'order_id',
        'status',
        'transaction_id',
        'provider',
        'price'
    ];

    protected $allowedFilters = [
        'created_at'
    ];
}
