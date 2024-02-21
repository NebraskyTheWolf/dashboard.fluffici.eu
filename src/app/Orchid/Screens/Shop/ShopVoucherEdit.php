<?php

namespace app\Orchid\Screens\Shop;

use App\Models\ShopCustomer;
use Faker\Core\DateTime;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class ShopVoucherEdit extends Screen
{
    public $voucher;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(\App\Models\ShopVouchers $voucher): iterable
    {
        return [
            'voucher' => $voucher
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->voucher->exists ? 'Edit voucher' : 'Create voucher';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.pencil')
                ->method('createOrUpdate'),
            Link::make('Generate Card')
                ->icon('bs.card')
                ->href(route('api.shop.voucher') . '?voucherCode=' . $this->voucher->code)
                ->canSee($this->voucher->exists)
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $this->voucher->code = Uuid::uuid4();

        return [
            Layout::rows([
                Group::make([
                    Relation::make('voucher.customer_id')
                        ->title('Customer')
                        ->help('Please select the assigned customer to this Voucher code.')
                        ->fromModel(ShopCustomer::class, 'email', 'customer_id')
                        ->required(),

                    DateTimer::make('voucher.expiration')
                        ->title('Please select the expiration')
                        ->allowInput()
                        ->enableTime()
                        ->format24hr()
                        ->required(),

                    CheckBox::make('voucher.restricted')
                        ->title('Restricted')
                        ->help('Is this voucher restricted?'),

                ])->alignStart(),

                Group::make([
                    Quill::make('voucher.note')
                        ->title('Note')
                        ->help('Please enter a note if needed otherwise let it empty.'),
                ])->alignCenter(),

                Group::make([
                    Input::make('voucher.money')
                        ->title('Amount in CZK')
                        ->type('number')
                        ->required(),

                    CheckBox::make('voucher.gift')
                        ->title('Gift')
                        ->help('Is this a gifted voucher?')
                ])->alignEnd(),
            ])->title('Voucher Information')
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->voucher->fill($request->get('voucher'))->save();

        Toast::info('You created a new voucher code.');

        return redirect()->route('platform.shop.vouchers');
    }
}
