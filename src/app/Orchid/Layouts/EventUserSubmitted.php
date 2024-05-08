<?php

namespace App\Orchid\Layouts;

use App\Models\EventAttachments;
use App\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EventUserSubmitted extends Table
{

    protected $target = 'users';


    protected function columns(): iterable
    {
        return [];
    }
}
