<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\OrderPayment;
use App\Models\ShopOrders;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

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
            TD::make('first_name', __('orders.table.first_name'))
                ->render(function (ShopOrders $shopOrders) {
                    return Link::make($shopOrders->first_name)
                        ->icon('bs.box-arrow-in-right')
                        ->href(route('platform.shop.orders.edit', $shopOrders));
                }),
            TD::make('last_name', __('orders.table.last_name')),
            TD::make('email', __('orders.table.email')),
            TD::make('status', __('orders.table.status'))
                ->render(function (ShopOrders $shopOrders) {
                    if ($shopOrders->status == "PROCESSING") {
                        return '<a class="ui blue label">'.__('orders.table.status.processing').'</a>';
                    } else if ($shopOrders->status == "CANCELLED") {
                        return '<a class="ui red label">'.__('orders.table.status.cancelled').'</a>';
                    } else if ($shopOrders->status == "REFUNDED") {
                        return '<div><a class="ui orange label">'.__('orders.table.status.refunded').'</a></div>';
                    } else if ($shopOrders->status == "DISPUTED") {
                        return '<a class="ui red label">'.__('orders.table.status.disputed').'</a>';
                    } else if ($shopOrders->status == "DELIVERED") {
                        return '<a class="ui green label">'.__('orders.table.status.delivered').'</a>';
                    } else if ($shopOrders->status == "ARCHIVED") {
                        return '<a class="ui brown label">'.__('orders.table.status.archived').'</a>';
                    } else if ($shopOrders->status == "COMPLETED") {
                        return '<div><a class="ui green label">'.__('orders.table.status.completed').'</a></div>';
                    } else if ($shopOrders->status == "OUTING") {
                        return '<a class="ui blue label">Payment at Outing <i class="loading cog icon"></i></a>';
                    }
                    return '<a class="ui purple label">'. $shopOrders->status . '</a>';
                }),
            TD::make('price_paid', __('orders.table.paid'))
                ->render(function (ShopOrders $shopOrders) {
                    $payment = OrderPayment::where('order_id', $shopOrders->order_id)->orderBy('created_at', 'desc');

                    if ($payment->exists()) {
                        $price = $payment->first()->price;

                        return '<div class="ui tag labels"><a class="ui label">' . $price . ' Kc</a></div>';
                    } else {
                        return '0 Kc';
                    }
                }),
            TD::make('payment_status', __('orders.table.payment_status'))
                ->render(function (ShopOrders $shopOrders) {
                    $payment = OrderPayment::where('order_id', $shopOrders->order_id)->orderBy('created_at', 'desc');

                    // if there is no payment record we say that is still in process
                    // Until the provider confirms the transactions

                    if ($payment->exists()) {
                        $status = $payment->first()->status;

                        if ($status == "CANCELLED") {
                            return '<a class="ui teal label">'.__('orders.table.payment_status.cancelled').'</a>';
                        } else if ($status == "REFUNDED") {
                            return '<a class="ui green label">'.__('orders.table.payment_status.paid').'</a>';
                        } else if ($status == "PAID") {
                            return '<a class="ui green label">'.__('orders.table.payment_status.paid').'</a>';
                        } else if ($status == "PARTIALLY_PAID") {
                            return '<a class="ui yellow label">'.__('orders.table.payment_status.partially_paid').'</a>';
                        } else if ($status == "UNPAID") {
                            return '<a class="ui red label">'.__('orders.table.payment_status.unpaid').'</a>';
                        }
                    }

                    return '<a class="ui blue label">'.__('orders.table.payment_status.await').' <i class="loading cog icon"></i></a>';
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.postcard';
    }

    protected function textNotFound(): string
    {
        return 'No order available.';
    }
}
