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

        return [
            'metrics' => [
                'outstanding_amount' => [
                    'key' => 'outstanding_amount',
                    'value' => number_format(OrderPayment::where('status', 'PAID')->sum('price') + Accounting::where('type', 'INCOME')->sum('amount') - Accounting::where('type', 'EXPENSE')->sum('amount')) . ' Kč',
                    'diff' => $this->diff(
                        OrderPayment::where('status', 'PAID')
                            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                            ->sum('price') +
                        Accounting::where('type', 'INCOME')
                            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                            ->sum('amount') -
                        Accounting::where('type', 'EXPENSE')
                            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                            ->sum('amount'),
                        OrderPayment::where('status', 'PAID')->sum('price') +
                        Accounting::where('type', 'INCOME')->sum('amount') -
                        Accounting::where('type', 'EXPENSE')->sum('amount'))
                ],
                'overdue_amount'   => [
                    'key' => 'overdue_amount',
                    'value' => number_format(OrderPayment::where('status', 'UNPAID')->sum('price')) . ' Kč',
                    'diff' => $this->diff(
                        OrderPayment::where('status', 'UNPAID')
                            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                            ->sum('price'),
                        OrderPayment::where('status', 'UNPAID')->sum('price'))
                ],
                'expenses' => [
                    'key' => 'expensed',
                    'value' => number_format(Accounting::where('type', 'EXPENSE')->sum('amount')) . ' Kč',
                    'diff' => $this->diff(
                        Accounting::where('type', 'EXPENSE')
                            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                            ->sum('amount'),
                        Accounting::where('type', 'EXPENSE')->sum('amount'))
                ]
            ],

            'income_ratio' => [
                OrderPayment::where('status', 'PAID')->sumByDays('price')->toChart('Shop Income'),
                Accounting::where('type', 'INCOME')->sumByDays('amount')->toChart('External Income')
            ],
            'external_expense' => [
                Accounting::where('type', 'EXPENSE')->sumByDays('amount')->toChart("External Expense")
            ],
            'accounting' => Accounting::orderBy('created_at', 'desc')->paginate()
        ];
    }

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

    public function commandBar(): iterable
    {
        return [
            Link::make('New Operation')
                ->icon('bs.piggy-bank')
                ->href(route('platform.accounting.new')),
        ];
    }

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

    public function diff($recent, $previous): float
    {
        if ($recent <= 0 || $previous <= 0)
            return 0.0;

        return (($recent-$previous)/$previous) * 100;
    }
}
