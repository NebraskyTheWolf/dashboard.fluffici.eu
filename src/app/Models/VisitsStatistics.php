<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class VisitsStatistics extends Model
{
    use Chartable, AsSource;

    public $table = 'visits_statistics';

    public $fillable = [
        'application_slug',
        'ip',
        'country',
        'path'
    ];
}
