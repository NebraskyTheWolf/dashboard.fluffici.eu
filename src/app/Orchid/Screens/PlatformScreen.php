<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

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
        return 'Welcome to your Dashboard.';
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
                'Pending Orders' => 'metrics.orders',
                'Pending Tickets' => 'metrics.tickets',
                'Visits' => 'metrics.visitors',
            ]),
        ];
    }

    private function calculateDiff($new, $old) : float {
        // Formula 
        // a - b = c
        // c / a * 100 = d

        return (($new - $old) / ($old * 100));
    }
}
