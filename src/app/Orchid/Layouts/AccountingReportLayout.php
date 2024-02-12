<?php

namespace App\Orchid\Layouts;

use App\Models\AccountingDocument;
use App\Models\TransactionsReport;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class AccountingReportLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sources';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('download', 'Action')
                ->render(function (AccountingDocument $reports) {
                    return Link::make('Download')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id . '&type=accounting');
                }),
            TD::make('report_id', 'Report ID')
                ->render(function (AccountingDocument $reports) {
                    return $reports->report_id;
                }),
            TD::make('created_at', 'Created At')
                ->render(function (AccountingDocument $reports) {
                    return Carbon::parse($reports->created_at)->diffForHumans();
                }),
            TD::make('delete', 'Delete')
                ->render(function (AccountingDocument $reports) {
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
        return 'No monthly accounting report yet.';
    }

    protected function subNotFound(): string
    {
        return 'The next report will be automatically generated in ' . Carbon::now()->endOfMonth()->diffForHumans();
    }
}
