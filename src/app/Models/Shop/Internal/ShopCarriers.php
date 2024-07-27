<?php

namespace App\Models\Shop\Internal;

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
