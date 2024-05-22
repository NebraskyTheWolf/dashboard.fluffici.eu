<?php

namespace App\Orchid\Screens;

use App\Models\WebsocketApps;
use App\Orchid\Layouts\RealTimeApps;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class RealTimeAppList extends Screen
{

    public function query(): iterable
    {
        return [
            'applications' => WebsocketApps::paginate()
        ];
    }


    public function name(): ?string
    {
        return 'Real time service(s)';
    }


    public function commandBar(): iterable
    {
        return [
            Button::make('New application')
                ->type(Color::SUCCESS)
                ->icon('bs.plus')
                ->route('platform.realtime.edit')
        ];
    }


    public function layout(): iterable
    {
        return [
            RealTimeApps::class
        ];
    }
}
