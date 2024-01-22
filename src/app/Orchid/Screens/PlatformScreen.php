<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\EventsInteresteds;
use App\Orchid\Layouts\ShopProfit;
use Illuminate\Support\Carbon;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

use App\Models\Pages;
use App\Models\ShopOrders;
use App\Models\ShopSupportTickets;

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
            ],
            'events' => [
                EventsInteresteds::averageByDays("id")->toChart('Interested'),
            ]
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Fluffici Admin Panel.';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Vítejte na dashbordu';
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
                'Nevyřízené objednávky' => 'metrics.orders',
                'Nevyřízené tikety podpory' => 'metrics.tickets',
                'Návštěvy' => 'metrics.visitors',
            ]),
            ShopProfit::make('dataset', 'Celkové dosavadní návštěvy.'),
            ShopProfit::make('events', 'Celková dosavadní aktivita v rámci akce.')
        ];
    }
}
