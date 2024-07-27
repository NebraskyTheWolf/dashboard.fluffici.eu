<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class EventsInteresteds extends Model{
    use AsSource, Chartable;
}
