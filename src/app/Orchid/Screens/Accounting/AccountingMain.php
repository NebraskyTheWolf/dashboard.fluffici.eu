<?php

namespace app\Orchid\Screens\Accounting;

use App\Models\Accounting;
use App\Orchid\Filters\FilterByDate;
use App\Models\OrderPayment;
use App\Orchid\Layouts\AccountingTracks;
use App\Orchid\Layouts\Shop\ShopProfit;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class AccountingMain extends Screen
{
    public function query(): iterable
    {
        $lastMonth = Carbon::now()->subMonth();
        $currentYear = Carbon::now()->year;

        return [
            'metrics' => [
                'outstanding_amount' => [
                    'key' => 'outstanding_amount',
                    'value' => number_format(OrderPayment::where('status', 'PAID')
                                ->whereMonth('created_at',$lastMonth)
                                ->whereYear('created_at', $currentYear)
                                ->sum('price') -
                        OrderPayment::where('status', 'REFUNDED')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('price') -
                        OrderPayment::where('status', 'UNPAID')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('price') +
                        Accounting::where('type', 'INCOME')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('amount') -
                        Accounting::where('type', 'EXPENSE')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('amount')) . ' Kč',
                    'diff' => $this->diff(
                        OrderPayment::all()->sum('price'),
                        OrderPayment::whereMonth('created_at', $lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('price')
                    ),
                ],
                'overdue_amount' => [
                    'key' => 'overdue_amount',
                    'value' => number_format(OrderPayment::where('status', 'UNPAID')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('price')) . ' Kč',
                    'diff' => $this->diff(
                        OrderPayment::where('status', 'UNPAID')->sum('price'),
                        OrderPayment::where('status', 'UNPAID')
                            ->whereMonth('created_at', $lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('price')
                    ),
                ],
                'expenses' => [
                    'key' => 'expenses',
                    'value' => number_format(Accounting::where('type', 'EXPENSE')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('amount')) . ' Kč',
                    'diff' => $this->diff(
                        Accounting::where('type', 'EXPENSE')->sum('amount'),
                        Accounting::where('type', 'EXPENSE')->sum('amount')
                            ->whereMonth('created_at',$lastMonth)
                            ->whereYear('created_at', $currentYear)
                            ->sum('amount')
                    ),
                ]
            ],
            'income_ratio' => [
                OrderPayment::where('status', 'PAID')->sumByDays('price')->toChart('Shop Income'),
                Accounting::where('type', 'INCOME')->sumByDays('amount')->toChart('External Income')
            ],
            'external_expense' => [
                OrderPayment::where('status', 'REFUNDED')->sumByDays('price')->toChart('Shop Refund'),
                OrderPayment::where('status', 'UNPAID')->sumByDays('price')->toChart('Shop Overdue'),
                Accounting::where('type', 'EXPENSE')->sumByDays('amount')->toChart("External Expense")
            ],
            'accounting' => Accounting::orderBy('created_at', 'desc')->paginate()
        ];
    }

    /**
     * Retrieves the name of the accounting entity, if available.
     *
     * @return string|null The name of the accounting entity, or null if the name is not available.
     */
    public function name(): ?string
    {
        return 'Accounting';
    }

    /**
     * Retrieves the permissions for a user.
     *
     * @return iterable|null A list of permissions assigned to the user.
     *   If no permissions are assigned, the function returns null.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.accounting',
        ];
    }

    /**
     * Generates an iterable array of command bar links.
     * Each link includes an icon and a href to a specific route.
     *
     * @return iterable An iterable array of command bar links.
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
     * Generates the layout for a report.
     *
     * @return iterable The layout of the report.
     *
     * The layout is an iterable containing the following elements:
     * 1. An array of metrics related to standing balance and overdue amounts.
     *    Each metric is represented by an associative array with the following key-value pairs:
     *     - 'Standing Balance': The metric for outstanding amount. The value is retrieved from 'metrics.outstanding_amount'.
     *     - 'Overdue': The metric for overdue amount. The value is retrieved from 'metrics.overdue_amount'.
     * 2. A ShopProfit instance representing the 'Net Income Ratio'. This instance is exported.
     * 3. A ShopProfit instance representing the 'Expenses'. This instance is exported.
     * 4. An instance of the AccountingTracks class.
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Standing Balance' => 'metrics.outstanding_amount',
                'Overdue' => 'metrics.overdue_amount'
            ]),

            ShopProfit::make('income_ratio' ,'Net Income Ratio')
                ->export(),

            ShopProfit::make('external_expense' ,'Expenses')
                ->export(),

            AccountingTracks::class
        ];
    }

    /**
     * Calculates the difference in percentage between two values.
     *
     * @param float|int $recent The recent value.
     * @param float|int $previous The previous value.
     *
     * @return float The difference in percentage between the recent and previous values.
     *   If either the recent or previous value is less than or equal to zero, the function returns 0.0.
     */
    public function diff($recent,$previous): float
    {
        if ($recent <= 0 || $previous <= 0)
            return 0.0;

        return (($recent-$previous)/$previous) * 100;
    }
}
