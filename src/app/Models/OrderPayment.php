<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;

class OrderPayment extends Model
{
    use HasFactory, Chartable;

    protected $table = "order_payment";
}
