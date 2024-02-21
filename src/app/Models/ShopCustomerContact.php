<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomerContact extends Model
{
    use HasFactory;

    public $table = 'shop_customer_contact';
    public $connection = 'shop';
}
