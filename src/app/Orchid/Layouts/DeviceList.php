<?php

namespace App\Orchid\Layouts;

use App\Models\DeviceAuthorization;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class DeviceList extends Table
{
    /**
     * Zdroj dat.
     *
     * Název klíče, ze kterého ho vytáhneme z dotazu.
     * Výsledky, které budou prvkami tabulky.
     *
     * @var string
     */
    protected $target = 'devices';
    /**
     * Získání buněk tabulky ke zobrazení.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', "Akce")
                ->render(function (DeviceAuthorization $authorization) {
                    return Button::make($authorization->restricted == 1 ? "Obnovit" : "Omezit")
                        ->icon('bs.trash')
                        ->method("restrictDevice",  [
                                'id' => $authorization->id
                        ])
                        ->download(true)
                        ->type(Color::PRIMARY);
                }),
            TD::make('linked_user', "Přiřazený uživatel")
                ->render(function (DeviceAuthorization $authorization) {
                    $user = User::where('id', $authorization->linked_user)->first();
                    if ($user) {
                        return Link::make($user->name)
                            ->icon('bs.caret-down')
                            ->route('platform.device.new', $authorization);
                    } else {
                        return 'Neznámý';
                    }
                }),
            TD::make('deviceId', "Identifikátor zařízení"),
            TD::make('status', "Stav zařízení")
        ];
    }
}
