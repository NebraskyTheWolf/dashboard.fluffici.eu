<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;

class OrderedProduct extends Model
{
    use HasFactory, Chartable;

    protected $table = "ordered_product";
}
