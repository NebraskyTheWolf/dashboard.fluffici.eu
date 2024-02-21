<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class OrderInvoice extends Model
{
    use AsSource;

    public $table = 'order_invoice';
    public $connection = 'shop';
}
