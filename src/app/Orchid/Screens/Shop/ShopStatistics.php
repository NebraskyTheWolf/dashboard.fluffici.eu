<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Customer\Order\OrderedProduct;
use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\Shop\Customer\Order\ShopOrders;
use App\Orchid\Layouts\Pie;
use App\Orchid\Layouts\Shop\ShopProfit;
use Carbon\Carbon;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ShopStatistics extends Screen
{
    /**
     * Queries the data for metrics, pie charts, dataset charts, and order charts.
     *
     * @return iterable The query result containing metrics, pie charts, dataset charts, and order charts.
     */
    public function query(): iterable
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        return [
            'metrics' => [
                'products' => [
                    'key' => 'products',
                    'value' => $this->sumQuantityOrderedProduct(),
                    'numeric' => true,
                    'icon' => 'bs.boxes'
                ],
                'overall' => [
                    'key' => 'overall',
                    'value' => number_format($this->calculateOverallPayment()) . ' Kč',
                    'diff' => $this->calculateDifference($this->sumPriceOrderPayment(), $this->sumPriceOrderPaymentByDate($currentYear, $currentMonth)),
                    'numeric' => true,
                    'icon' => 'bs.safe'
                ],
                'monthly' => [
                    'key' => 'monthly',
                    'value' => number_format($this->sumPriceOrderPaymentByDate($currentYear, $currentMonth)) . ' Kč',
                    'diff' => $this->calculateDifference($this->sumPriceOrderPaymentByDate($currentYear, $currentMonth), $this->sumPriceOrderPaymentByDate($currentYear, $previousMonth)),
                    'numeric' => true,
                    'icon' => 'bs.safe2'
                ],
            ],
            'order' => [
                ShopOrders::where('status', 'COMPLETED')->averageByDays('id')->toChart(__('statistics.screen.chart.item.completed')),
                ShopOrders::where('status', 'REFUNDED')->averageByDays('id')->toChart(__('statistics.screen.chart.item.refunded')),
                ShopOrders::where('status', 'DISPUTED')->averageByDays('id')->toChart(__('statistics.screen.chart.item.disputed')),
                ShopOrders::where('status', 'PROCESSING')->averageByDays('id')->toChart(__('statistics.screen.chart.item.processing')),
                ShopOrders::where('status', 'OUTING')->averageByDays('id')->toChart('Outing'),
            ]
        ];
    }

    /**
     * Calculates the total quantity of ordered products.
     *
     * @return int The total quantity of ordered products.
     */
    private function sumQuantityOrderedProduct(): int
    {
        return OrderedProduct::sum('quantity');
    }

    /**
     * Calculates the sum of the prices for order payments based on their status.
     *
     * @return float The total sum of the prices for order payments.
     */
    private function sumPriceOrderPayment(): float
    {
        return OrderPayment::where('status', 'PAID')->sum('price')
            - OrderPayment::where('status', 'UNPAID')->sum('price')
            - OrderPayment::where('status', 'REFUNDED')->sum('price');
    }

    /**
     * Calculates the sum of price for order payments in the specified month.
     *
     * @param int $year The year of the month.
     * @param Carbon $month The month.
     * @return float The sum of price for order payments.
     */
    private function sumPriceOrderPaymentByDate(int $year, Carbon $month): float
    {
        return OrderPayment::where('status', 'PAID')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month->month)
            ->sum('price');
    }

    /**
     * Calculates the overall payment after subtracting unpaid, refunded, and cancelled payments.
     *
     * @return float The overall payment.
     */
    private function calculateOverallPayment(): float
    {
        return OrderPayment::where('status', 'PAID')->sum('price')
            - OrderPayment::where('status', 'UNPAID')->sum('price')
            - OrderPayment::where('status', 'REFUNDED')->sum('price')
            - OrderPayment::where('status', 'CANCELLED')->sum('price');
    }

    /**
     * Calculates the percentage difference between two given numbers.
     *
     * @param float $recent The recent number.
     * @param float $previous The previous number.
     * @return float The percentage difference between the recent and previous numbers.
     *               If either the recent or previous number is less than or equal to zero, returns 0.0.
     */
    private function calculateDifference(float $recent, float $previous): float
    {
        if ($previous <= 0) {
            return 0.0;
        }

        return (($recent - $previous) / abs($previous)) * 100;
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('statistics.screen.title');
    }

    /**
     * Get the permissions for accessing shop statistics.
     *
     * @return iterable|null The permissions for accessing shop statistics.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.shop.statistics.read',
        ];
    }

    /**
     * Retrieves the list of commands for the command bar.
     *
     * @return iterable The list of commands for the command bar.
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Generates the layout for the statistics screen.
     *
     * @return iterable The layout containing metrics, pie charts, dataset charts, and order charts.
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                __('statistics.screen.layout.metrics.products') => 'metrics.products',
                __('statistics.screen.layout.metrics.overall') => 'metrics.overall',
                __('statistics.screen.layout.metrics.monthly') => 'metrics.monthly',
            ]),
            ShopProfit::make('order', __('statistics.screen.layout.chart.weekly_orders')),
        ];
    }
}
