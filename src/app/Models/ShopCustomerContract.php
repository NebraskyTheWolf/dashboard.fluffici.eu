<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomerContract extends Model
{
    use HasFactory;

    public $table = 'shop_customer_contract';
    public $connection = 'shop';
}
