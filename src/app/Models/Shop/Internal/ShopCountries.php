<?php

namespace App\Models\Shop\Internal;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCountries extends Model
{
    use AsSource;
    public $connection = 'shop';
    protected $fillable = [
        'country_name',
        'iso_code'
    ];
}
