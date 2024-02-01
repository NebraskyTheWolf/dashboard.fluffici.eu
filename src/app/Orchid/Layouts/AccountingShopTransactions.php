<?php

namespace App\Orchid\Layouts;

use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\ShopProducts;
use App\Models\ShopSales;
use Carbon\Carbon;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AccountingShopTransactions extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'transactions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('status', __('orders.table.payment_status'))
                ->render(function (OrderPayment $shopOrders) {
                    if ($shopOrders->status == "REFUNDED") {
                        return '<a class="ui green label">'.__('orders.table.payment_status.paid').'</a>';
                    } else if ($shopOrders->status == "PAID") {
                        return '<a class="ui green label">'.__('orders.table.payment_status.paid').'</a>';
                    }

                    return '<a class="ui red label">'.__('orders.table.payment_status.unpaid').'</a>';
                }),
            TD::make('transaction_id', 'Transaction ID')
                ->render(function (OrderPayment $payment) {
                    return '<a href="https://provider.com/transactions/"' . $payment->transaction_id . '> <i class="caret square right outline icon"></i> Check </a>';
                }),
            TD::make('provider', 'Provider'),
            TD::make('price', 'Price')
                ->render(function (OrderPayment $payment) {
                    if ($payment->status == "PAID") {
                        $missing = $this->isMissing($payment);
                        $over = $this->isOverPaid($payment);

                        if (!$missing > 0.1) {
                            return '<a class="ui yellow label">Missing ' . $missing . ' Kc</a>';
                        } else if (!$over > 0.1) {
                            return '<a class="ui yellow label">Over paid of ' . $over . ' Kc</a>';
                        }
                    }

                    return $payment->price . ' Kc';
                }),
            TD::make('created_at', 'Created At')
                ->render(function (OrderPayment $payment) {
                    return Carbon::parse($payment->created_at)->diffForHumans();
                })
        ];
    }

    protected function calculate(\App\Models\OrderPayment $payment): float
    {
        $orderPrd = OrderedProduct::where('order_id', $payment->order_id)->firstOrFail();
        $product = ShopProducts::where('id', $orderPrd->product_id)->firstOrFail();
        $sale = ShopSales::where('product_id', $product->id);
        $carrier = OrderCarrier::where('order_id', $payment->order_id);

        $salePrice = 0;

        if ($sale->exists()) {
            $salePrice = $product->price * ($sale->firstOrFail()->reduction / 100);
        }

        if ($carrier->exists()) {
            return $product->price - $salePrice + $carrier->firstOrFail()->price;
        } else {
            return $product->price - $salePrice;
        }
    }

    protected function isMissing(\App\Models\OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($amount < $payment->price) {
            return $amount - $payment->price;
        }

        return 0;
    }

    protected function isOverPaid(\App\Models\OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($amount > $payment->price) {
            return $amount - $payment->price;
        }

        return 0;
    }
}
