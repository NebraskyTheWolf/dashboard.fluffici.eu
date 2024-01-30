<?php

namespace App\Orchid\Screens\Shop;

use App\Models\ShopCarriers;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ShopCarrierList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'carriers' => ShopCarriers::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Carriers';
    }

    public function permission(): iterable
    {
        return [
            'platform.shop.carriers.read'
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
            Link::make(__('sales.screen.button.add'))
                ->icon('bs.plus-circle')
                ->href(route('platform.shop.carriers.edit'))
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
            \App\Orchid\Layouts\Shop\ShopCarrierList::class
        ];
    }
}
