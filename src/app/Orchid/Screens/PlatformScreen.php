<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\EventsInteresteds;
use App\Models\Pages;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;
use App\Models\VisitsStatistics;
use App\Orchid\Layouts\Pie;
use App\Orchid\Layouts\Shop\ShopProfit;
use App\Orchid\Layouts\VisitTracking;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
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
                'visitors' => [
                    'key' => 'visits',
                    'value' => number_format(intval(Pages::sum('visits')))
                ],
                'tickets'   => [
                    'key' => 'tickets',
                    'value' => number_format(ShopSupportTickets::where('status', 'PENDING')->count())
                ],
                'orders'   => [
                    'key' => 'orders',
                    'value' => number_format(ShopOrders::where('status', 'PENDING')->count())
                ],
            ],
            'dataset' => [
                Pages::averageByDays('visits')->toChart('Visits'),
                VisitsStatistics::averageByDays('id')->toChart('Platform Visits')
            ],
            'pie' => [
                VisitsStatistics::sumByDays('country')->toChart('Countries')
            ],
            'pie_platform' => [
                VisitsStatistics::sumByDays('application_slug')->toChart('Application')
            ],
            'pie_path' => [
                VisitsStatistics::sumByDays('path')->toChart('Routes')
            ],
            'events' => [
                EventsInteresteds::averageByDays("id")->toChart('Interested'),
            ],

            'visits' => VisitsStatistics::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('main.screen.title');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return __('main.screen.descriptions', [
            'name' => Auth::user()->name
        ]);
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                __('main.screen.metrics.order') => 'metrics.orders',
                __('main.screen.metrics.tickets') => 'metrics.tickets',
                __('main.screen.metrics.visitors') => 'metrics.visitors',
            ]),
            ShopProfit::make('dataset', __('main.screen.chart.visitors'))->type("line"),
            VisitTracking::class
        ];
    }
}
