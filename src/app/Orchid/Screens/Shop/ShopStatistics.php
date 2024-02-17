<?php

namespace App\Orchid\Screens\Shop;

use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\ShopOrders;
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
                ],
                'overall' => [
                    'key' => 'overall',
                    'value' => number_format(
                            OrderPayment::where('status', 'PAID')->sum('price') -
                            OrderPayment::where('status', 'UNPAID')->sum('price') -
                            OrderPayment::where('status', 'REFUNDED')->sum('price') -
                            OrderPayment::where('status', 'CANCELLED')->sum('price')) . ' Kč',
                    'diff' => $this->diff($this->sumPriceOrderPayment(), $this->sumPriceOrderPaymentSubMonth($currentYear, $currentMonth))
                ],
                'monthly' => [
                    'key' => 'monthly',
                    'value' => $this->sumPriceOrderPaymentByDate($currentYear, $currentMonth) . ' Kč',
                    'diff' => $this->diff($this->sumPriceOrderPaymentByDate($currentYear, $currentMonth),
                        $this->sumPriceOrderPaymentByDate($currentYear, $previousMonth))
                ],
            ],
            'pie' => [
                OrderedProduct::sumByDays('product_id')->toChart('Product'),
            ],
            'dataset' => [
                OrderedProduct::sumByDays('price')->toChart('Price'),
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
        return number_format(OrderedProduct::all()->sum('quantity'));
    }

    /**
     * Calculates the sum of the prices for order payments based on their status.
     *
     * @return float The total sum of the prices for order payments.
     */
    private function sumPriceOrderPayment(): float
    {
        return OrderPayment::where('status', 'PAID')->sum('price') -
            OrderPayment::where('status', 'UNPAID')->sum('price') -
            OrderPayment::where('status', 'REFUNDED')->sum('price');
    }

    /**
     * Calculates the sum of price for order payments in the previous month.
     *
     * @param int $year The year of the previous month.
     * @param mixed $month The previous month.
     * @return float The sum of price for order payments in the previous
     *               month, after subtracting the values of unpaid, refunded,
     *               and cancelled order payments.
     */
    private function sumPriceOrderPaymentSubMonth(int $year, mixed $month): float
    {
        return OrderPayment::where('status', 'PAID')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('price') -
            OrderPayment::where('status', 'UNPAID')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('price') -
            OrderPayment::where('status', 'REFUNDED')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('price');
    }

    /**
     * Calculates the sum of price for order payments by date.
     *
     * @param int $year The year of the date.
     * @param mixed $month The month of the date. Can be integer or string representation of the month.
     * @return float The sum of price for order payments in Czech koruna (Kč).
     */
    private function sumPriceOrderPaymentByDate(int $year, mixed $month) : float
    {
        return number_format(
                OrderPayment::where('status', 'PAID')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('price')
            );
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

            Pie::make('pie',  __('statistics.screen.layout.chart.product_frequency')),
            ShopProfit::make('dataset',  __('statistics.screen.layout.chart.weekly')),
            ShopProfit::make('order', __('statistics.screen.layout.chart.weekly_orders')),
        ];
    }

    /**
     * Calculates the percentage difference between two given numbers.
     *
     * @param float $recent The recent number.
     * @param float $previous The previous number.
     * @return float The percentage difference between the recent and previous numbers.
     *               If either the recent or previous number is less than or equal to zero, returns 0.0.
     */
    public function diff(float $recent, float $previous): float
    {
        if ($recent <= 0 || $previous <= 0)
            return 0.0;

        return (($recent-$previous)/abs($previous)) * 100;
    }
}
