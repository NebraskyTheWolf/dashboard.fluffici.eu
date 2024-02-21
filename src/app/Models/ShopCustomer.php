<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomer extends Model
{
    use HasFactory;

    public $table = 'shop_customer';
    public $connection = 'shop';

    public $hidden = [
        'password'
    ];
}
