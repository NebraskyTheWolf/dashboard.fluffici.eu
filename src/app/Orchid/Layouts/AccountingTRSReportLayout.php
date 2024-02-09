<?php

namespace App\Orchid\Layouts;

use App\Models\ShopReports;
use App\Models\TransactionsReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
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
            TD::make('download', 'Action')
                ->render(function (TransactionsReport $reports) {
                    return Link::make('Download')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id . '&type=transactions');
                }),
            TD::make('report_id', 'Report ID')
                ->render(function (TransactionsReport $reports) {
                    return $reports->report_id;
                }),
            TD::make('created_at', 'Created At')
                ->render(function (TransactionsReport $reports) {
                    return Carbon::parse($reports->created_at)->diffForHumans();
                }),
            TD::make('delete', 'Delete')
                ->render(function (TransactionsReport $reports) {
                    return Button::make('Delete')
                        ->confirm(__('common.modal.confirm'))
                        ->method('delete', [
                            'reportId' => $reports->report_id
                        ])
                        ->download(true)
                        ->icon('bs.trash');
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.clipboard-data';
    }

    protected function textNotFound(): string
    {
        return 'No monthly report yet.';
    }

    protected function subNotFound(): string
    {
        return 'The next report will be automatically generated.';
    }
}
