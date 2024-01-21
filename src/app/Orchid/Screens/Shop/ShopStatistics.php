<?php

namespace App\Orchid\Screens\Shop;

use App\Models\ShopOrders;
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
        $start = Carbon::now();

        return [
            'metrics' => [
                'products' => [
                    'key' => 'products',
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->sum())
                ],
                'overall'   => [
                    'key' => 'overall',
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->sum('total_price'))
                ],
                'monthly'   => [
                    'key' => 'monthly',
                    'value' => number_format(ShopOrders::where('status', 'COMPLETED')->whereBetween('created_at',
                                $start->startOfMonth()->format('Y-m-d'),
                                $start->endOfMonth()->format('Y-m-d')
                            )->sum('total_price'))
                ],
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Shop Growth';
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

            ShopProfit::class
        ];
    }
}
