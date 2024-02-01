<?php

namespace app\Orchid\Screens\Accounting;

use App\Models\Accounting;
use App\Orchid\Filters\FilterByDate;
use App\Models\OrderPayment;
use App\Orchid\Layouts\Shop\ShopProfit;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class AccountingMain extends Screen
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
                'outstanding_amount' => [
                    'key' => 'outstanding_amount',
                    'value' => '+ ' . number_format(OrderPayment::where('status', 'PAID')->sum('price') + Accounting::where('type', 'INCOME')->sum('amount')) . ' Kc'
                ],
                'overdue_amount'   => [
                    'key' => 'overdue_amount',
                    'value' => number_format(OrderPayment::where('status', 'UNPAID')->sum('price')) . ' Kc'
                ],
                'expenses' => [
                    'key' => 'expensed',
                    'value' => '- ' . number_format(Accounting::where('type', 'EXPENSE')->sum('amount')) . ' Kc'
                ]
            ],

            'income_ratio' => [
                OrderPayment::where('status', 'PAID')->sumByDays('price')->toChart('Shop Income'),
                Accounting::where('type', 'INCOME')->sumByDays('amount')->toChart(fn(Accounting $accounting) => $accounting->source)
            ],
            'external_expense' => [
                Accounting::where('type', 'EXPENSE')->sumByDays('amount')->toChart(fn(Accounting $accounting) => $accounting->source)
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
        return 'Accounting';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.accounting',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('New Operation')
                ->icon('bs.piggy-bank')
                ->href(route('platform.accounting.new')),
        ];
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
                'Outstanding Amount' => 'metrics.outstanding_amount',
                'Overdue' => 'metrics.overdue_amount'
            ]),

            ShopProfit::make('income_ratio' ,'Net Income Ratio')
                ->export(),

            ShopProfit::make('external_expense' ,'Expenses')
                ->export(),
        ];
    }
}
