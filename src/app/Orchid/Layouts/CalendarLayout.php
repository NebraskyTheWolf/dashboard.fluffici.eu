<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Calendar;

class CalendarLayout extends Calendar
{
    public string $title = "Test";
    public string $description = "This is a test calendar.";

    public bool $editable = true;

    public bool $googleCalendar = true;
    public string $googleCalendarURL = '389ae5e4402000bdfd22e2742a69bf74ff0b4c18209e2a1ad99d4015d34afd8c@group.calendar.google.com';
    public string $googleCalendarSecret = 'AIzaSyDoUlR9Wnn_8uyBHrkTmO7gcs9yXZPz9JM';
}
