<?php

namespace App\Orchid\Screens\Shop;

use App\Orchid\Layouts\ShopOrderLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ShopOrders extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'shop_orders' => \App\Models\ShopOrders::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Orders';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.orders.read',
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
            Button::make('Refresh')
                ->icon('bs.arrow-clockwise')
                ->method('refresh')
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
            ShopOrderLayout::class
        ];
    }

    public function refresh() {
        Toast::info('The data has been refreshed.');
        return redirect()->route('platform.shop.orders');
    }
}
