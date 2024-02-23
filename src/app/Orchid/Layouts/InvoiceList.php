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
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'invoices';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('download', 'Action')
                ->render(function (OrderInvoice $reports) {
                    return Link::make('Download')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id . '&type=invoice');
                }),

            TD::make('report_id', 'Invoice ID'),
            TD::make('order_id', 'Order ID'),

            TD::make('created_at', 'Created At')
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
        return 'No invoice yet.';
    }
}
