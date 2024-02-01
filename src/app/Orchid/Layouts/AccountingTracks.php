<?php

namespace App\Orchid\Layouts;

use App\Models\Accounting;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AccountingTracks extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'accounting';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('type', 'Type')
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
            TD::make('source', 'Source'),
            TD::make('amount', 'Amount')
                ->render(function (Accounting $accounting) {
                    return number_format($accounting->amount);
                }),
            TD::make('created_at')
                ->render(function (Accounting $accounting) {
                    return Carbon::parse($accounting->created_at)->diffForHumans();
                })
        ];
    }
}
