<?php

namespace app\Orchid\Layouts\Shop;

use App\Models\ShopVouchers;
use Carbon\Carbon;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

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
                ->render(function (ShopVouchers $vouchers) {
                    return Password::make('voucher')
                            ->title('Click to reveal.')
                            ->value($vouchers->code);
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
        return 'bs.cash-coin';
    }

    protected function textNotFound(): string
    {
        return 'There is no voucher yet.';
    }
}
