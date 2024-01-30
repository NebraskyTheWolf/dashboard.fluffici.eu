<?php

namespace App\Orchid\Screens\Shop;

use App\Models\ShopCountries;
use App\Orchid\Layouts\Shop\ShopCountriesLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ShopCountriesList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'countries' => ShopCountries::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Countries';
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.countries.read'
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('New')
                ->icon('bs.plus')
                ->href(route('platform.shop.countries.edit'))
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ShopCountriesLayout::class
        ];
    }
}
