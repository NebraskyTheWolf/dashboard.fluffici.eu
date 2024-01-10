<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopSales extends Model
{
    use AsSource;

    protected $fillable = [
        'product_id',
        'product_type',
        'reduction',
        'deleted_at'
    ];
}
