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
            'shop_orders' => \App\Models\ShopOrders::orderBy('created_at', 'desc')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('orders.screen.title');
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
            Button::make(__('orders.screen.button.refresh'))
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
        Toast::info(__('orders.screen.toast.refresh'));
        return redirect()->route('platform.shop.orders');
    }
}
