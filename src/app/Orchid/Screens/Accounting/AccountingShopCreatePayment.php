<?php

namespace app\Orchid\Screens\Accounting;

use App\Events\UpdateAudit;
use App\Models\OrderPayment;
use App\Models\ShopOrders;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class AccountingShopCreatePayment extends Screen
{
    public $payment;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(OrderPayment $payment): iterable
    {
        return [
            'payment' => $payment
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Record a new payment';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create')
                ->icon('bs.plus')
                ->method('createOrUpdate')
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
           \Orchid\Support\Facades\Layout::rows([
               Select::make('payment.status')
                   ->title('Payment Status')
                   ->options([
                       'PAID' => 'Paid',
                       'UNPAID' => 'Unpaid',
                       'REFUNDED' => 'Refunded',
                       'CANCELLED' => 'Cancelled'
                   ]),
               Relation::make('payment.order_id')
                   ->title('Select the order.')
                   ->fromModel(ShopOrders::class, 'first_name', 'order_id'),
               Input::make('payment.price')
                   ->title('Price')
           ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->payment->transaction_id = Uuid::uuid4();
        $this->payment->provider = 'Fluffici';


        $this->payment->fill($request->get('payment'))->save();

        Toast::info('You recorded a new payment manually.');

        event(new UpdateAudit('payment', 'Recorded a new payment', Auth::user()->name));

        return redirect()->route('platform.accounting.transactions');
    }
}
