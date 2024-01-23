<?php

namespace App\Orchid\Screens\Shop;

use App\Models\OrderPayment;
use App\Models\OrderedProduct;
use App\Models\Pages;
use App\Models\ShopOrders;
use App\Orchid\Layouts\Pie;
use App\Orchid\Layouts\ShopProfit;
use Carbon\Carbon;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ShopStatistics extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'metrics' => [
                'products' => [
                    'key' => 'products',
                    'value' => number_format(OrderedProduct::all()->sum('quantity'))
                ],
                'overall'   => [
                    'key' => 'overall',
                    'value' => number_format(OrderPayment::where('status', 'PAID')->sum('price')) . ' Kc'
                ],
                'monthly'   => [
                    'key' => 'monthly',
                    'value' => number_format(OrderPayment::where('status', 'PAID')->whereBetween('created_at', [
                            Carbon::now()->subDay(1),
                            Carbon::now()->subDay(30)
                    ])->sum('price')) . ' Kc'
                ],
            ],
            'pie' => [
                OrderedProduct::sumByDays('product_name')->toChart('Product'),
            ],
            'dataset' => [
                ShopOrders::sumByDays('total_price')->toChart('Price'),
            ],
            'order' => [
                ShopOrders::where('status', 'COMPLETED')->averageByDays('total_price')->toChart(__('statistics.screen.chart.item.completed')),
                ShopOrders::where('status', 'REFUNDED')->averageByDays('total_price')->toChart(__('statistics.screen.chart.item.refunded')),
                ShopOrders::where('status', 'DISPUTED')->averageByDays('total_price')->toChart(__('statistics.screen.chart.item.disputed')),
                ShopOrders::where('status', 'PROCESSING')->averageByDays('total_price')->toChart(__('statistics.screen.chart.item.processing')),
            ]
        ];
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

    public function permission(): ?iterable
    {
        return [
            'platform.shop.statistics.read',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
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
}
