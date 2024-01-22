<?php

namespace App\Orchid\Screens\Shop;

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
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->sum('products'))
                ],
                'overall'   => [
                    'key' => 'overall',
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->sum('total_price')) . ' Kc'
                ],
                'monthly'   => [
                    'key' => 'monthly',
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->whereBetween('created_at', [
                            Carbon::now()->subDay(1),
                            Carbon::now()->subDay(30)
                    ])->sum('total_price')) . ' Kc'
                ],
            ],
            'pie' => [
                ShopOrders::sumByDays('country')->toChart('Country'),
            ],
            'dataset' => [
                ShopOrders::sumByDays('total_price')->toChart('Price'),
            ],
            'order' => [
                ShopOrders::where('status', 'COMPLETED')->averageByDays('total_price')->toChart('Completed'),
                ShopOrders::where('status', 'REFUNDED')->averageByDays('total_price')->toChart('Refunded'),
                ShopOrders::where('status', 'DISPUTED')->averageByDays('total_price')->toChart('Disputed'),
                ShopOrders::where('status', 'PROCESSING')->averageByDays('total_price')->toChart('Processing'),
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
        return 'Growth';
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
                'Products sold' => 'metrics.products',
                'Overall profit' => 'metrics.overall',
                'Profit for this month' => 'metrics.monthly',
            ]),

            Pie::make('pie', 'Most frequent country'),
            ShopProfit::make('dataset', 'Overall profit the past 7 days.'),
            ShopProfit::make('order', 'Overall orders the past 7 days.'),
        ];
    }
}
