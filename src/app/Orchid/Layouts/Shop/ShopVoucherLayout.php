<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Customer\ShopVouchers;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ShopVoucherLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'vouchers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('code', 'Code')
                ->render(function (ShopVouchers $reports) {

                    if (Carbon::parse($reports->expiration)->isPast()) {
                        return Button::make('Download')
                            ->icon('bs.caret-down-square')
                            ->type(Color::PRIMARY)
                            ->disabled();
                    } else {
                        return Link::make('Download')
                            ->icon('bs.caret-down-square')
                            ->type(Color::SUCCESS)
                            ->download()
                            ->href(route('api.shop.voucher') . '?voucherCode=' . $reports->code);
                    }
                }),
            TD::make('money', 'Amount')
                ->render(function (ShopVouchers $vouchers) {
                    return number_format($vouchers->money) . ' Kc';
                }),
            TD::make('created_at', 'Created At')
                ->render(function (ShopVouchers $vouchers) {
                    return Carbon::parse($vouchers->created_at)->diffForHumans();
                }),
            TD::make('updated_at', 'Updated At')
                ->render(function (ShopVouchers $vouchers) {
                    return Carbon::parse($vouchers->updated_at)->diffForHumans();
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.qr-code-scan';
    }

    protected function textNotFound(): string
    {
        return 'There is no voucher yet.';
    }
}
