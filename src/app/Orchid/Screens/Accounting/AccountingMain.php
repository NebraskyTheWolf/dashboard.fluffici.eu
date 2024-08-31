<?php

namespace App\Orchid\Screens\Accounting;

use App\Models\Shop\Accounting\Accounting;
use App\Models\Shop\Customer\Order\OrderPayment;
use App\Orchid\Layouts\AccountingTracks;
use App\Orchid\Layouts\Shop\ShopProfit;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
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
                    'value' => number_format($this->calculateOutstandingAmount($lastMonth, $currentYear)) . ' Kč',
                    'diff' => $this->calculateDifference(
                        $this->calculateOutstandingAmount($lastMonth, $currentYear),
                        $this->calculateTotalPayments()
                    ),
                    'numeric' => true,
                    'icon' => 'bs.piggy-bank'
                ],
                'year_balance' => [
                    'key' => 'year_balance',
                    'value' => number_format($this->calculateYearBalance($currentYear)) . ' Kč',
                    'diff' => $this->calculateDifference(
                        $this->calculateYearBalance($currentYear),
                        $this->calculateTotalPayments()
                    ),
                    'numeric' => true,
                    'icon' => 'bs.piggy-bank'
                ],
                'spent_month' => [
                    'key' => 'spent_month',
                    'value' => number_format($this->calculateSpentMonth($lastMonth, $currentYear)) . ' Kč',
                    'diff' => $this->calculateDifference(
                        $this->calculateSpentMonth($lastMonth, $currentYear),
                        $this->calculateTotalPayments()
                    ),
                    'numeric' => true,
                    'icon' => 'bs.graph-down-arrow'
                ],
                'overdue_amount' => [
                    'key' => 'overdue_amount',
                    'value' => number_format($this->calculateOverdueAmount($lastMonth, $currentYear)) . ' Kč',
                    'diff' => $this->calculateDifference(
                        $this->calculateOverdueAmount($lastMonth, $currentYear),
                        $this->calculateTotalOverdue()
                    ),
                    'numeric' => true,
                    'icon' => 'bs.clock-history'
                ]
            ],
            'income_ratio' => [
                OrderPayment::where('status', 'PAID')->sumByDays('price')->toChart('Shop Income'),
                Accounting::where('type', 'INCOME')->where('is_recurring', 0)->sumByDays('amount')->toChart('External Income')
            ],
            'external_expense' => [
                OrderPayment::where('status', 'REFUNDED')->sumByDays('price')->toChart('Refund'),
                OrderPayment::where('status', 'UNPAID')->sumByDays('price')->toChart('Unpaid'),
                Accounting::where('type', 'EXPENSE')->where('is_recurring', 0)->sumByDays('amount')->toChart("External Expense")
            ],
            'accounting' => Accounting::orderBy('created_at', 'desc')->paginate()
        ];
    }

    private function calculateOutstandingAmount($lastMonth, $currentYear): float
    {
        return OrderPayment::where('status', 'PAID')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->sum('price') -
            OrderPayment::where('status', 'REFUNDED')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->sum('price') -
            OrderPayment::where('status', 'UNPAID')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->sum('price') +
            Accounting::where('type', 'INCOME')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->where('is_recurring', 0)
                ->sum('amount') -
            Accounting::where('type', 'EXPENSE')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->where('is_recurring', 0)
                ->sum('amount');
    }

    private function calculateYearBalance($currentYear): float
    {
        return OrderPayment::where('status', 'PAID')
                ->whereYear('created_at', $currentYear)
                ->sum('price') -
            OrderPayment::where('status', 'REFUNDED')
                ->whereYear('created_at', $currentYear)
                ->sum('price') -
            OrderPayment::where('status', 'UNPAID')
                ->sum('price') +
            Accounting::where('type', 'INCOME')
                ->whereYear('created_at', $currentYear)
                ->where('is_recurring', 0)
                ->sum('amount') -
            Accounting::where('type', 'EXPENSE')
                ->whereYear('created_at', $currentYear)
                ->where('is_recurring', 0)
                ->sum('amount');
    }

    private function calculateSpentMonth($lastMonth, $currentYear): float
    {
        return OrderPayment::where('status', 'REFUNDED')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $currentYear)
                ->sum('price') +
            OrderPayment::where('status', 'UNPAID')
                ->sum('price') +
            Accounting::where('type', 'EXPENSE')
                ->whereYear('created_at', $currentYear)
                ->where('is_recurring', 0)
                ->sum('amount');
    }

    private function calculateOverdueAmount($lastMonth, $currentYear): float
    {
        return OrderPayment::where('status', 'UNPAID')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $currentYear)
            ->sum('price');
    }

    private function calculateTotalPayments(): float
    {
        return OrderPayment::sum('price');
    }

    private function calculateTotalOverdue(): float
    {
        return OrderPayment::where('status', 'UNPAID')->sum('price');
    }

    private function calculateDifference($recent, $previous): float
    {
        if ($recent <= 0 || $previous <= 0) {
            return 0.0;
        }

        return (($recent - $previous) / $previous) * 100;
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
            Link::make('New Transaction')
                ->icon('bs.piggy-bank')
                ->href(route('platform.accounting.new')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Year Balance' => 'metrics.year_balance',
                'Outstanding Amount' => 'metrics.outstanding_amount',
                'Spent This Month' => 'metrics.spent_month',
                'Overdue Amount' => 'metrics.overdue_amount'
            ]),

            ShopProfit::make('income_ratio', 'Net Income Ratio')
                ->export(),

            ShopProfit::make('external_expense', 'Expenses')
                ->export(),

            AccountingTracks::class
        ];
    }
}
