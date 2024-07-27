<?php

namespace App\Orchid\Layouts;

use App\Models\Shop\Accounting\Accounting;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AccountingTracks extends Table
{
    /**
     * Zdroj dat.
     *
     * Název klíče, ze kterého se získává z dotazu.
     * Výsledky, které budou prvky tabulky.
     *
     * @var string
     */
    protected $target = 'accounting';
    /**
     * Získejte buňky tabulky, které mají být zobrazeny.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('type', 'Typ')
                ->render(function (Accounting $accounting) {
                    $link =  Link::make($accounting->type);
                    if ($accounting->type == 'INCOME') {
                        $link->icon('bs.graph-up-arrow');
                    } else {
                        $link->icon('bs.graph-down-arrow');
                    }
                    $link->href(route('platform.accounting.new', $accounting));
                    return $link;
                }),
            TD::make('source', 'Zdroj'),
            TD::make('amount', 'Částka')
                ->render(function (Accounting $accounting) {
                    return number_format($accounting->amount) . ' Kč';
                }),
            TD::make('created_at')
                ->render(function (Accounting $accounting) {
                    return Carbon::parse($accounting->created_at)->diffForHumans();
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.paperclip';
    }

    protected function textNotFound(): string
    {
        return 'Zatím žádná aktivita.';
    }

    protected function subNotFound(): string
    {
        return 'Můžete vytvořit novou operaci.';
    }
}
