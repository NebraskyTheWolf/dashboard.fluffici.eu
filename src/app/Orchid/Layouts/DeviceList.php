<?php

namespace App\Orchid\Layouts;

use App\Models\DeviceAuthorization;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class DeviceList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'devices';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', "Action")
                ->render(function (DeviceAuthorization $authorization) {
                    return Button::make($authorization->restricted == 1 ? "Reinstate" : "Restrict")
                        ->icon('bs.trash')
                        ->method("restrictDevice")
                        ->download(true)
                        ->type(Color::PRIMARY);
                }),
            TD::make('linked_user', "Assigned User")
                ->render(function (DeviceAuthorization $authorization) {
                    $user = User::where('id', $authorization->linked_user)->first();
                    if ($user) {
                        return $user->name;
                    } else {
                        return 'Unknown';
                    }
                }),
            TD::make('deviceId', "Device Identifier"),
            TD::make('status', "Device Status")
        ];
    }
}
