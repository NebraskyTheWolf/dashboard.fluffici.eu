<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ProductTax extends Model
{
    use AsSource;

    public $table = 'product_tax';
    public $connection = 'shop';

    public $fillable = [
        'product_id',
        'tax_id'
    ];
}
