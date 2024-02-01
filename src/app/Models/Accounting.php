<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Accounting extends Model
{
    use AsSource, Filterable, Chartable;

    public $table = 'accounting';

    protected $allowedFilters = [
        'created_at'
    ];
}
