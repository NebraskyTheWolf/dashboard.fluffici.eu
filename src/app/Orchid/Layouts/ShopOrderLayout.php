<?php

namespace App\Orchid\Layouts;

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
                    return Link::make($shopOrders->first_name);
                        //->route('platform.events.edit', $shopOrders);
                }),
            TD::make('last_name', 'Last name'),
            TD::make('email', 'Email'),
            TD::make('status', 'Status')
                ->render(function (ShopOrders $shopOrders) {
                    if ($shopOrders->status == "PROCESSING") {
                        return '<a class="ui blue label">Processing</a>';
                    } else if ($shopOrders->status == "CANCELLED") {
                        return '<a class="ui red label">Cancelled</a>';
                    } else if ($shopOrders->status == "REFUNDED") {
                        return '<div><a class="ui orange label">Refunded</a></div>';
                    } else if ($shopOrders->status == "DISPUTED") {
                        return '<a class="ui red label">Disputed</a>';
                    } else if ($shopOrders->status == "DELIVERED") {
                        return '<a class="ui green label">Delivered</a>';
                    } else if ($shopOrders->status == "ARCHIVED") {
                        return '<a class="ui brown label">Archived</a>';
                    } else if ($shopOrders->status == "COMPLETED") {
                        return '<div><a class="ui green label">Completed</a></div>';
                    }
                    return '<a class="ui purple label">'. $shopOrders->status . '</a>';
                }),
            TD::make('price_paid', 'Paid')
                ->render(function (ShopOrders $shopOrders) {
                     return '<div class="ui tag labels"><a class="ui label">' . $shopOrders->price_paid . ' Kc</a></div>';
                }),
            TD::make('payment_status', 'Payment status')
                ->render(function (ShopOrders $shopOrders) {
                    if ($shopOrders->payment_status == "PROCESSING") {
                        return '<a class="ui blue label">Processing</a>';
                    } else if ($shopOrders->payment_status == "CANCELLED") {
                        return '<a class="ui teal label">Cancelled</a>';
                    } else if ($shopOrders->payment_status == "PAID") {
                        return '<a class="ui green label">Paid</a>';
                    } else if ($shopOrders->payment_status == "UNPAID") {
                        return '<a class="ui red label">Unpaid</a>';
                    }
                    return '<a class="ui teal label">'. $shopOrders->payment_status . '</a>';
                })
        ];
    }
}
