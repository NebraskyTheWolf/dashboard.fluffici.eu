<?php

namespace App\Orchid\Layouts;

use App\Models\ShopCarriers;
use Carbon\Carbon;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopCarrierList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'carriers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('slug'),
            TD::make('carrierName'),
            TD::make('created_at')
                ->render(function (ShopCarriers $carriers) {
                    return Carbon::parse($carriers->created_at)->diffForHumans();
                }),
            TD::make('updated_at')
                ->render(function (ShopCarriers $carriers) {
                    return Carbon::parse($carriers->updated_at)->diffForHumans();
                }),
        ];
    }
}
