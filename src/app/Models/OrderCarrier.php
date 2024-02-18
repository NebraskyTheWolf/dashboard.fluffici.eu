<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;

class OrderCarrier extends Model
{
    use HasFactory, Chartable;

    public $connection = 'shop';

    public $table = 'order_carrier';
}
