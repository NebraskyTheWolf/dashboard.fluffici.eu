<?php

namespace App\Orchid\Layouts;

use App\Models\WebsocketApps;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RealTimeApps extends Table
{

    protected $target = 'applications';


    protected function columns(): iterable
    {
        return [
            TD::make('application_name', 'App name')
                ->render(function (WebsocketApps $apps) {
                    return Link::make($apps->application_name)
                            ->icon('bs.pencil')
                            ->route('platform.realtime.edit', $apps);
                }),
            TD::make('key', 'Public key')
        ];
    }
}
