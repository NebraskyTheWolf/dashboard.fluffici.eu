<?php

namespace App\Orchid\Layouts;

use App\Models\ShopReports;
use App\Models\TransactionsReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Symfony\Component\HttpFoundation\Request;

class AccountingTRSReportLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'transactions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('download', 'Akce')
                ->render(function (TransactionsReport $reports) {
                    return Link::make('Stáhnout')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id . '&type=transactions');
                }),
            TD::make('report_id', 'ID zprávy')
                ->render(function (TransactionsReport $reports) {
                    return $reports->report_id;
                }),
            TD::make('delete', 'Smazat')
                ->render(function (TransactionsReport $reports) {
                    return Button::make('Smazat')
                        ->confirm(__('common.modal.confirm'))
                        ->method('delete', [
                            'reportId' => $reports->report_id
                        ])
                        ->download(true)
                        ->icon('bs.trash');
                }),
            TD::make('created_at', 'Vytvořeno')
                ->render(function (TransactionsReport $report) {
                    return Input::make('created_at')
                        ->relativeTime(true)
                        ->timestamp(Carbon::parse($report->created_at)->toDateTimeString())
                        ->parsedTime(Carbon::parse($report->created_at)->format("D, d M Y H:i:s"));
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.clipboard-data';
    }

    protected function textNotFound(): string
    {
        return 'Zatím nebyla vytvořena žádná měsíční zpráva.';
    }

    protected function subNotFound(): string
    {
        return 'Další zpráva bude automaticky vygenerována ' . Carbon::now()->endOfMonth()->diffForHumans();
    }
}
