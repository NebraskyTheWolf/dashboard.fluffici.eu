<?php

namespace App\Orchid\Layouts;

use App\Models\OrderPayment;
use App\Models\ShopOrders;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ShopOrderLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'shop_orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('first_name', 'First name')
                ->render(function (ShopOrders $shopOrders) {
                    return Link::make($shopOrders->first_name)
                        ->route('platform.shop.orders.edit', $shopOrders);
                }),
            TD::make('last_name', 'Last name'),
            TD::make('email', 'Email'),
            TD::make('status', 'Status')
                ->render(function (ShopOrders $shopOrders) {
                    if ($shopOrders->status == "PROCESSING") {
                        return '<a class="ui blue label">Zpracováváno</a>';
                    } else if ($shopOrders->status == "CANCELLED") {
                        return '<a class="ui red label">Zrušeno</a>';
                    } else if ($shopOrders->status == "REFUNDED") {
                        return '<div><a class="ui orange label">Vrácené peníze</a></div>';
                    } else if ($shopOrders->status == "DISPUTED") {
                        return '<a class="ui red label">Sporné</a>';
                    } else if ($shopOrders->status == "DELIVERED") {
                        return '<a class="ui green label">Doručeno</a>';
                    } else if ($shopOrders->status == "ARCHIVED") {
                        return '<a class="ui brown label">Archivováno</a>';
                    } else if ($shopOrders->status == "COMPLETED") {
                        return '<div><a class="ui green label">Dokončeno</a></div>';
                    }
                    return '<a class="ui purple label">'. $shopOrders->status . '</a>';
                }),
            TD::make('price_paid', 'Paid')
                ->render(function (ShopOrders $shopOrders) {
                    $payment = OrderPayment::where('order_id', $shopOrders->order_id)->firstOrFail();

                    if ($payment->exists()) {
                        return '<div class="ui tag labels"><a class="ui label">' . $payment->price . ' Kc</a></div>';
                    } else {
                        return '0 Kc';
                    }
                }),
            TD::make('payment_status', 'Payment status')
                ->render(function (ShopOrders $shopOrders) {
                    $payment = OrderPayment::where('order_id', $shopOrders->order_id)->firstOrFail();

                    if ($payment->exists()) {
                        if ($payment->status == "CANCELLED") {
                            return '<a class="ui teal label">Zrušeno</a>';
                        } else if ($payment->status == "PAID") {
                            return '<a class="ui green label">Zaplaceno</a>';
                        } else if ($payment->status == "UNPAID") {
                            return '<a class="ui red label">Nezaplaceno</a>';
                        }
                    }

                    return '<a class="ui blue label">Čekáme na zpracování... <i class="loading cog icon"></i></a>';
                })
        ];
    }
}
