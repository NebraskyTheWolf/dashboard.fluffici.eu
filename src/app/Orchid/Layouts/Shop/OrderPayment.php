<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\ShopProducts;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderPayment extends Table
{
    protected $target = 'orderPayment';

    protected function columns(): iterable
    {
        return [
            TD::make('status',  __('orders.table.payment_status'))
                ->render(function (\App\Models\OrderPayment $shopOrders) {
                    if ($shopOrders->status == "CANCELLED") {
                        return '<a class="ui teal label">'.__('orders.table.payment_status.cancelled').'</a>';
                    } else if ($shopOrders->status == "PAID") {
                        return '<a class="ui green label">'.__('orders.table.payment_status.paid').'</a>';
                    } else if ($shopOrders->status == "UNPAID") {
                        return '<a class="ui red label">'.__('orders.table.payment_status.unpaid').'</a>';
                    } else if ($shopOrders->status == "REFUNDED") {
                        return '<a class="ui yellow label">'.__('orders.table.payment_status.refunded').'</a>';
                    } else if ($shopOrders->status == "DISPUTED") {
                        return '<a class="ui yellow label">'.__('orders.table.payment_status.disputed').'</a>';
                    } else if ($shopOrders->status == "PARTIALLY_PAID") {
                        return '<a class="ui yellow label">'.__('orders.table.status.partially_paid').'</a>';
                    }

                    return '<a class="ui blue label">'.__('orders.table.payment_status.await').' <i class="loading cog icon"></i></a>';
                }),
            TD::make('transaction_id', 'Transaction ID')
                ->render(function (\App\Models\OrderPayment $payment) {
                    if ($payment->transaction_id === null) {
                        return '<a><i class="exclamation triangle icon"></i> This payment need to be checked on the provider!</a>';
                    }

                    return "<a href=\"https://provider.com/transactions/" . $payment->transaction_id . "\"> <i class=\"caret square right outline icon\"></i> Check </a>";
                }),
            TD::make('provider')
                ->render(function (\App\Models\OrderPayment $payment) {
                    if ($payment->provider === null) {
                        return 'No provider detected.';
                    }
                    return $payment->provider;
                }),
            TD::make('price')
                ->render(function (\App\Models\OrderPayment $payment) {
                    if ($payment->status == "PAID") {
                        $missing = $this->isMissing($payment);
                        $over = $this->isOverPaid($payment);

                        // 0.01 Precision

                        if ($missing > 0.01) {
                            return '<a class="ui red label">Missing ' . $missing . ' Kc</a>';
                        } else if ($over > 0.01) {
                            return '<a class="ui yellow label">Over paid of ' . $over . ' Kc</a>';
                        }
                    }

                    return $payment->price . ' Kc';
                }),
            TD::make('remaining_balance', 'To Pay')
                ->render(function (\App\Models\OrderPayment $payment) {
                    if ($payment->status == "PAID" || $payment->status == "PARTIALLY_PAID") {
                        $remainingBalance = $this->calculate($payment) - $payment->price;
                        return '<a class="ui green label">To Pay ' . $remainingBalance . ' Kc</a>';
                    } else {
                        return '<a class="ui blue label">To Pay ' . $this->calculate($payment) .' Kc</i></a>';
                    }
                })
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.exclamation-triangle';
    }

    protected function textNotFound(): string {
        return '<a class="ui teal label">Awaiting payment... <i class="loading cog icon"></i></a>';
    }

    protected function subNotFound(): string
    {
        return 'No payment has been found yet.';
    }

    /**
     * Calculate the total price of an order payment.
     *
     * This method calculates the total price of an order payment by summing the normalized prices of the ordered products.
     * It also includes the price of the order carrier, if applicable.
     *
     * @param \App\Models\OrderPayment $payment The order payment.
     * @return float The total price of the order payment.
     */
    protected function calculate(\App\Models\OrderPayment $payment): float
    {
        // Eager load to optimize database queries
        $orderedProducts = OrderedProduct::with('product')->where('order_id', $payment->order_id)->get();
        $totalPrice = $orderedProducts->sum(function ($orderedProduct) {
            return $orderedProduct->product->getNormalizedPrice();
        });

        $carrier = OrderCarrier::where('order_id', $payment->order_id)->first();

        if ($carrier) {
            $totalPrice += $carrier->price;
        }

        return $totalPrice;
    }

    /**
     * Checks if the given OrderPayment has a missing amount.
     *
     * @param \App\Models\OrderPayment $payment The OrderPayment object to check.
     * @return float Returns the missing amount or 0.0 if no amount is missing.
     */
    protected function isMissing(\App\Models\OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($payment->price < $amount) {
            return $amount - $payment->price;
        }

        return 0.0;
    }

    /**
     * Checks if the given OrderPayment is overpaid.
     *
     * @param \App\Models\OrderPayment $payment The OrderPayment object to check.
     * @return float Returns the overpaid amount or 0.0 if no overpayment is detected.
     */
    protected function isOverPaid(\App\Models\OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($payment->price > $amount) {
            return $payment->price - $amount;
        }

        return 0.0;
    }
}
