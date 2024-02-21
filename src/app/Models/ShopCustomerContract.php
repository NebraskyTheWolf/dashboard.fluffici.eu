<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCustomerContract extends Model
{
    use AsSource;
    public $table = 'shop_customer_contract';
    public $connection = 'shop';
}
