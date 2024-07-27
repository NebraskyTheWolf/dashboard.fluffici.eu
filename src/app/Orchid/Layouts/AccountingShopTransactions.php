<?php

namespace App\Orchid\Layouts;

use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\Shop\Internal\ShopSales;
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
                ->render(function (OrderPayment $payment) {
                    return $this->renderPaymentStatus($payment->status);
                }),
            TD::make('transaction_id', 'Transaction ID')
                ->render(function (OrderPayment $payment) {
                    return $this->renderTransactionId($payment->transaction_id);
                }),
            TD::make('provider', 'Provider'),
            TD::make('price', 'Price')
                ->render(function (OrderPayment $payment) {
                    return $this->renderPrice($payment);
                }),
            TD::make('created_at', 'Created At')
                ->render(function (OrderPayment $payment) {
                    return Carbon::parse($payment->created_at)->diffForHumans();
                })
        ];
    }

    private function renderPaymentStatus(string $status): string
    {
        $statusMap = [
            'REFUNDED' => ['class' => 'green', 'label' => __('orders.table.payment_status.paid')],
            'PAID' => ['class' => 'green', 'label' => __('orders.table.payment_status.paid')],
            'UNPAID' => ['class' => 'red', 'label' => __('orders.table.payment_status.unpaid')],
        ];

        $statusInfo = $statusMap[$status] ?? ['class' => 'red', 'label' => __('orders.table.payment_status.unpaid')];

        return '<a class="ui ' . $statusInfo['class'] . ' label">' . $statusInfo['label'] . '</a>';
    }

    private function renderTransactionId(?string $transactionId): string
    {
        if ($transactionId === null) {
            return '<a><i class="exclamation triangle icon"></i> This payment needs to be checked with the provider!</a>';
        }

        return '<a href="https://provider.com/transactions/' . $transactionId . '"> <i class="caret square right outline icon"></i> Check </a>';
    }

    private function renderPrice(OrderPayment $payment): string
    {
        $missing = $this->isMissing($payment);
        $overpaid = $this->isOverPaid($payment);

        if ($payment->status === 'PAID') {
            if ($missing > 0.1) {
                return '<a class="ui yellow label">Missing ' . $missing . ' Kc</a>';
            } elseif ($overpaid > 0.1) {
                return '<a class="ui yellow label">Overpaid by ' . $overpaid . ' Kc</a>';
            }
        }

        return $payment->price . ' Kc';
    }

    protected function calculate(OrderPayment $payment): float
    {
        $orderedProducts = $payment->order->orderedProducts;
        $totalPrice = 0;

        foreach ($orderedProducts as $orderedProduct) {
            $product = $orderedProduct->product;
            $sale = ShopSales::where('product_id', $product->id)->first();
            $salePrice = 0;

            if ($sale) {
                $salePrice = $product->price * ($sale->reduction / 100);
            }

            $totalPrice += ($product->price - $salePrice) * $orderedProduct->quantity;
        }

        $carrier = $payment->order->carrier;

        if ($carrier) {
            $totalPrice += $carrier->price;
        }

        return $totalPrice;
    }

    protected function isMissing(OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($amount > $payment->price) {
            return $amount - $payment->price;
        }

        return 0;
    }

    protected function isOverPaid(OrderPayment $payment): float
    {
        $amount = $this->calculate($payment);

        if ($amount < $payment->price) {
            return $payment->price - $amount;
        }

        return 0;
    }

    protected function iconNotFound(): string
    {
        return 'bs.cart-dash';
    }

    protected function textNotFound(): string
    {
        return 'No transactions found.';
    }

    protected function subNotFound(): string
    {
        return 'No transactions have been recorded yet.';
    }
}
