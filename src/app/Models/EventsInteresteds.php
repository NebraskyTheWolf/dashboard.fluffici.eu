<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class EventsInteresteds extends Model{
    use AsSource, Chartable;

    public $connection = 'akce';

}
