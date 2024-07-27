<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class CalendarEvent extends Model
{

    use AsSource;

    public $table = 'calendar_event';

    public $fillable = [
        'title',
        'description',
        'start',
        'end'
    ];
}
