<?php

namespace App\Orchid\Screens\Shop;

use App\Orchid\Layouts\Shop\ShopVoucherLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ShopVouchers extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'vouchers' => \App\Models\Shop\Customer\ShopVouchers::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('vouchers.screen.title');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.shop.vouchers.read',
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
            Link::make('New Voucher')
                ->icon('bs.coin')
                ->href(route('platform.shop.vouchers.edit'))
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
            ShopVoucherLayout::class
        ];
    }
}
