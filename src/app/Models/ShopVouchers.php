<?php

namespace App\Models;

use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopVouchers extends Model
{
    use AsSource, Chartable;

    protected $fillable = [
        'code',
        'money'
    ];
}
