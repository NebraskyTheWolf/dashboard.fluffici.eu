<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCustomer extends Model
{
    use AsSource;

    public $table = 'shop_customer';
    public $connection = 'shop';

    public $hidden = [
        'password'
    ];
}
