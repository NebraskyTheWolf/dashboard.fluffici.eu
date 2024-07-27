<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;

class EventUserSubmitted extends Table
{

    protected $target = 'users';


    protected function columns(): iterable
    {
        return [];
    }
}
