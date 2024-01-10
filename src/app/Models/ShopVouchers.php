<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopVouchers extends Model
{
    use AsSource;

    protected $fillable = [
        'code',
        'money'
    ];
}
