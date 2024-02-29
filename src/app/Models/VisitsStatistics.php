<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitsStatistics extends Model
{

    public $table = 'visits_statistics';

    public $fillable = [
        'application_slug',
        'ip',
        'country',
        'path'
    ];
}
