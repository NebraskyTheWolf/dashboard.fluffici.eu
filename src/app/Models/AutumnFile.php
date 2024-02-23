<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Orchid\Screen\AsSource;

class AutumnFile extends Model
{
    use AsSource;

    public $connection = 'autumn';
    public $collection = 'attachments';

    public $fillable = [
        'reported',
        'deleted',
        'dmca'
    ];
}
