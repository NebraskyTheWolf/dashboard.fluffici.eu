<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Internal\ShopCarriers;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
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
            TD::make('slug')
                ->render(function (ShopCarriers $carriers) {
                    return Link::make($carriers->slug)
                        ->icon('bs.pencil')
                        ->href(route('platform.shop.carriers.edit', $carriers));
                }),
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

    protected function iconNotFound(): string
    {
        return 'bs.truck';
    }

    protected function textNotFound(): string
    {
        return 'No carriers available.';
    }
}
