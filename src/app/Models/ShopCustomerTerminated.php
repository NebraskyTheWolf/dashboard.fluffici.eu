<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCustomerTerminated extends Model
{
    use AsSource;
    public $table = 'shop_customer_terminated';
    public $connection = 'shop';
}
