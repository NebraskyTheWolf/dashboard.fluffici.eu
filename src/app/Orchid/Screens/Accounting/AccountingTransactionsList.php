<?php

namespace app\Orchid\Screens\Accounting;

use App\Models\Shop\Customer\Order\OrderPayment;
use App\Orchid\Layouts\AccountingShopTransactions;
use Orchid\Screen\Actions\Link;
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
            'transactions' => OrderPayment::orderBy('created_at', 'desc')->paginate()
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
            Link::make('New Transaction')
                ->icon('bs.plus')
                ->href(route('platform.accounting.transactions.new'))
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
            AccountingShopTransactions::class
        ];
    }
}
