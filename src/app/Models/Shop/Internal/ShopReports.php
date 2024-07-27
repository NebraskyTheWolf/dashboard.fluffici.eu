<?php

namespace App\Models\Shop\Internal;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopReports extends Model
{
    use AsSource;
    public $connection = 'shop';
}
