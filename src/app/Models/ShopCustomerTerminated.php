<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomerTerminated extends Model
{
    use HasFactory;

    public $table = 'shop_customer_terminated';
    public $connection = 'shop';
}
