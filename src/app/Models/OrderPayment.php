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

    protected $table = "order_payment";

    protected $allowedFilters = [
        'created_at'
    ];
}
