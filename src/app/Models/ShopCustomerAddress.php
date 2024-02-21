<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomerAddress extends Model
{
    use HasFactory;

    public $table = 'shop_customer_address';
    public $connection = 'shop';
}
