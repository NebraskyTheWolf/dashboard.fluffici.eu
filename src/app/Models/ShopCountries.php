<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopCountries extends Model
{
    use AsSource;
    public $connection = 'shop';
    protected $fillable = [
        'country_name',
        'iso_code'
    ];
}
