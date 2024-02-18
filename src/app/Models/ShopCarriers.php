<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCarriers extends Model {
    use AsSource;
    public $connection = 'shop';
    protected $fillable = [
        'slug',
        'carrierName',
        'carrierDelay',
        'carrierPrice'
    ];
}
