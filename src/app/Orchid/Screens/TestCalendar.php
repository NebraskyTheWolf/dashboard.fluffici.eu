<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\CalendarLayout;
use Orchid\Screen\Screen;

class TestCalendar extends Screen
{

    public function query(): iterable
    {
        return [];
    }


    public function name(): ?string
    {
        return 'Test Calendar Feature';
    }


    public function commandBar(): iterable
    {
        return [];
    }


    public function layout(): iterable
    {
        return [
            CalendarLayout::class
        ];
    }
}
