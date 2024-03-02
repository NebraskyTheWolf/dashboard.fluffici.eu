<?php

namespace App\Orchid\Layouts;

use App\Models\OrderInvoice;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class InvoiceList extends Table
{
    /**
     * Zdroj dat.
     *
     * Název klíče pro jeho načtení z dotazu.
     * Výsledky, které budou prvky tabulky.
     *
     * @var string
     */
    protected $target = 'invoices';
    /**
     * Získat buňky tabulky, které budou zobrazeny.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('download', 'Akce')
                ->render(function (OrderInvoice $reports) {
                    return Link::make('Stáhnout')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id . '&type=invoice');
                }),

            TD::make('report_id', 'ID faktury'),
            TD::make('order_id', 'ID objednávky'),

            TD::make('created_at', 'Vytvořeno')
                ->render(function (OrderInvoice $reports) {
                    return Carbon::parse($reports->created_at)->diffForHumans();
                }),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.journals';
    }

    protected function textNotFound(): string
    {
        return 'Zatím žádná faktura.';
    }
}
