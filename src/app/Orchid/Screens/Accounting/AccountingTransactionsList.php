<?php

namespace app\Orchid\Screens\Accounting;

use App\Models\OrderPayment;
use App\Orchid\Filters\FilterByDate;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class AccountingTransactionsList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'transactions' => OrderPayment::filters(FilterByDate::class)->simplePaginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Transactions';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.accounting.transactions',
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
            Button::make('New Transaction')
                ->icon('bs.plus')
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
