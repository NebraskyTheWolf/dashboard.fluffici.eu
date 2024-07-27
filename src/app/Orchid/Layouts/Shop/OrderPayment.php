<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Customer\Order\OrderPayment as OrderPaymentModel;
use App\Models\Shop\Customer\Order\OrderedProduct;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderPayment extends Table
{
    protected $target = 'orderPayment';

    protected function columns(): iterable
    {
        return [
            TD::make('status', __('orders.table.payment_status'))
                ->render(function (OrderPaymentModel $payment) {
                    return $this->renderPaymentStatus($payment->status);
                }),

            TD::make('transaction_id', 'Transaction ID')
                ->render(function (OrderPaymentModel $payment) {
                    return $this->renderTransactionId($payment->transaction_id);
                }),

            TD::make('provider', 'Provider')
                ->render(function (OrderPaymentModel $payment) {
                    return $payment->provider ?? 'Provider not detected';
                }),

            TD::make('price', 'Price')
                ->render(function (OrderPaymentModel $payment) {
                    return $this->renderPrice($payment);
                }),

            TD::make('remaining_balance', 'Remaining Balance')
                ->render(function (OrderPaymentModel $payment) {
                    return $this->renderRemainingBalance($payment);
                }),
        ];
    }

    private function renderPaymentStatus(string $status): string
    {
        $statusMap = [
            'CANCELLED' => ['class' => 'teal', 'label' => __('orders.table.payment_status.cancelled')],
            'PAID' => ['class' => 'green', 'label' => __('orders.table.payment_status.paid')],
            'UNPAID' => ['class' => 'red', 'label' => __('orders.table.payment_status.unpaid')],
            'REFUNDED' => ['class' => 'yellow', 'label' => __('orders.table.status.refunded')],
            'DISPUTED' => ['class' => 'yellow', 'label' => __('orders.table.payment_status.disputed')],
            'PARTIALLY_PAID' => ['class' => 'yellow', 'label' => __('orders.table.status.partially_paid')],
            'AWAIT' => ['class' => 'blue', 'label' => __('orders.table.payment_status.await')],
        ];

        $statusInfo = $statusMap[$status] ?? ['class' => 'blue', 'label' => __('orders.table.payment_status.await')];

        return '<a class="ui ' . $statusInfo['class'] . ' label">' . $statusInfo['label'] . ' <i class="loading cog icon"></i></a>';
    }

    private function renderTransactionId(?string $transactionId): string
    {
        if ($transactionId === null) {
            return '<a><i class="exclamation triangle icon"></i> This payment needs to be checked with the provider!</a>';
        }

        return '<a href="https://provider.com/transactions/' . $transactionId . '"> Check </a>';
    }

    private function renderPrice(OrderPaymentModel $payment): string
    {
        $priceInfo = $payment->price . ' Kc';
        if ($payment->status === 'PAID') {
            $missing = $this->isMissing($payment);
            $overpaid = $this->isOverPaid($payment);

            if ($missing > 0.01) {
                return '<a class="ui red label">Missing ' . $missing . ' Kc</a>';
            } elseif ($overpaid > 0.01) {
                return '<a class="ui yellow label">Overpaid by ' . $overpaid . ' Kc</a>';
            }
        }
        return $priceInfo;
    }

    private function renderRemainingBalance(OrderPaymentModel $payment): string
    {
        $remainingBalance = number_format($this->calculateTotalAmount($payment) - $this->getTotalPaidAmount($payment->order_id));
        return '<a class="ui green label">Remaining ' . $remainingBalance . ' Kc</a>';
    }

    protected function calculateTotalAmount(OrderPaymentModel $payment): float
    {
        $orderedProducts = OrderedProduct::with('product')->where('order_id', $payment->order_id)->get();
        $totalPrice = $orderedProducts->sum(fn($orderedProduct) => $orderedProduct->product->getNormalizedPrice());

        $carrier = $payment->order->carrier;

        if ($carrier) {
            $totalPrice += $carrier->price;
        }

        return $totalPrice;
    }

    protected function isMissing(OrderPaymentModel $payment): float
    {
        $totalAmount = $this->calculateTotalAmount($payment);

        if ($payment->price < $totalAmount) {
            return $totalAmount - $this->getTotalPaidAmount($payment->order_id);
        }

        return 0.0;
    }

    protected function isOverPaid(OrderPaymentModel $payment): float
    {
        $totalAmount = $this->calculateTotalAmount($payment);

        if ($payment->price > $totalAmount) {
            return $this->getTotalPaidAmount($payment->order_id) - $totalAmount;
        }

        return 0.0;
    }

    protected function getTotalPaidAmount(string $orderId): float
    {
        $payments = OrderPaymentModel::where('order_id', $orderId)
            ->whereIn('status', ['PAID', 'PARTIALLY_PAID'])
            ->get();

        return $payments->sum('price');
    }

    protected function iconNotFound(): string
    {
        return 'bs.exclamation-triangle';
    }

    protected function textNotFound(): string
    {
        return '<a class="ui teal label">Waiting for payment... <i class="loading cog icon"></i></a>';
    }

    protected function subNotFound(): string
    {
        return 'No payments found yet.';
    }
}
