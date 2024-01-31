<?php

namespace app\Orchid\Layouts\Shop;

use App\Models\ShopReports;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class ShopReportLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'reports';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('download', 'Action')
                ->render(function (ShopReports $reports) {
                    return Link::make('Download')
                        ->icon('bs.caret-down-square')
                        ->type(Color::SUCCESS)
                        ->download()
                        ->href(route('api.shop.report') . '?reportId=' . $reports->report_id);
                }),
            TD::make('report_id', 'Report ID')
                ->render(function (ShopReports $reports) {
                    return $reports->report_id;
                }),
            TD::make('created_at', 'Created At')
                ->render(function (ShopReports $reports) {
                    return Carbon::parse($reports->created_at)->diffForHumans();
                }),
            TD::make('delete', 'Delete')
                ->render(function (ShopReports $reports) {
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
