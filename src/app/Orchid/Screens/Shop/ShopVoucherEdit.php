<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Customer\ShopCustomer;
use App\Models\Shop\Customer\ShopVouchers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Validator;

class ShopVoucherEdit extends Screen
{
    public $voucher;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param ShopVouchers $voucher
     * @return array
     */
    public function query(ShopVouchers $voucher): iterable
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
        return $this->voucher->exists ? 'Edit Voucher' : 'Create Voucher';
    }

    /**
     * The screen's action buttons.
     *
     * @return array
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
     * @return array
     */
    public function layout(): iterable
    {
        if (!$this->voucher->exists) {
            $this->voucher->code = Uuid::uuid4();
        }

        return [
            Layout::rows([
                Group::make([
                    Relation::make('voucher.customer_id')
                        ->title('Customer')
                        ->help('Please select the assigned customer to this Voucher code.')
                        ->fromModel(ShopCustomer::class, 'email')
                        ->required(),

                    DateTimer::make('voucher.expiration')
                        ->title('Expiration Date')
                        ->allowInput()
                        ->enableTime()
                        ->format24hr()
                        ->required(),

                    CheckBox::make('voucher.restricted')
                        ->title('Restricted')
                        ->help('Is this voucher restricted?')
                        ->sendTrueOrFalse(),

                ])->alignStart(),

                Group::make([
                    Quill::make('voucher.note')
                        ->title('Note')
                        ->help('Please enter a note if needed, otherwise leave it empty.'),
                ])->alignCenter(),

                Group::make([
                    Input::make('voucher.money')
                        ->title('Amount in CZK')
                        ->type('number')
                        ->required(),

                    CheckBox::make('voucher.gift')
                        ->title('Gift')
                        ->help('Is this a gifted voucher?')
                        ->sendTrueOrFalse()
                ])->alignEnd(),
            ])->title('Voucher Information')
        ];
    }

    /**
     * Create or update a voucher code.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {
        $data = $request->get('voucher');

        $validator = Validator::make($data, [
            'customer_id' => 'required|exists:shop_customers,id',
            'expiration' => 'required|date',
            'money' => 'required|numeric|min:0',
            'restricted' => 'boolean',
            'gift' => 'boolean',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Toast::error('Please correct the errors in the form.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->voucher->fill($data)->save();

        Toast::info($this->voucher->exists ? 'Voucher updated successfully.' : 'Voucher created successfully.');

        return redirect()->route('platform.shop.vouchers');
    }
}
