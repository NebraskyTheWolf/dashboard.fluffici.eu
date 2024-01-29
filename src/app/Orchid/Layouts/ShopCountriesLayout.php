<?php

namespace App\Orchid\Layouts;

use App\Models\ShopCountries;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ShopCountriesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'countries';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('iso_code', 'ISO Code')
                ->render(function (ShopCountries $countries) {
                    return Link::make($countries->iso_code)
                        ->icon('bs.globe')
                        ->href(route('platform.shop.countries.edit', $countries));
                }),
            TD::make('country_name', 'Country')
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.passport';
    }

    protected function textNotFound(): string
    {
        return 'There is no country yet.';
    }
}
