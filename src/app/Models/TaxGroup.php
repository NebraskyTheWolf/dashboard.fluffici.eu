<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class TaxGroup extends Model
{
    use AsSource;

    public $table = 'tax_group';
    public $connection = 'shop';
    public $fillable = [
        'name',
        'percentage'
    ];
}
