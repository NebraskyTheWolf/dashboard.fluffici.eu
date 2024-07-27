<?php

namespace app\Orchid\Screens\Accounting;

use App\Models\Shop\Customer\Order\OrderInvoice;
use App\Orchid\Layouts\InvoiceList;
use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class AccountingInvoiceList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'invoices' => OrderInvoice::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Invoices';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.accounting.invoices',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            InvoiceList::class
        ];
    }
}
