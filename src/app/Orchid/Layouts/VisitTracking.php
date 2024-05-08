<?php

namespace App\Orchid\Layouts;

use App\Models\VisitsStatistics;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class VisitTracking extends Table
{

    protected $target = 'visits';


    protected function columns(): iterable
    {
        return [
            TD::make('application_slug', 'Platform'),
            TD::make('ip', 'IP'),
            TD::make('country', 'Country'),
            TD::make('path', 'Path'),
            TD::make('created_at', "Created At")
                ->render(function (VisitsStatistics $statistics) {
                    return Input::make('created_at')->timestamp($statistics->created_at)->relativeTime(true);
                })
        ];
    }
}
