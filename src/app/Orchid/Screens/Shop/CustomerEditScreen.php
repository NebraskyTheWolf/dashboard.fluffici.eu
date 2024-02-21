<?php

namespace App\Orchid\Screens\Shop;

use App\Events\UpdateAudit;
use App\Models\ShopCustomer;
use App\Models\ShopOrders;
use App\Models\ShopVouchers;
use App\Orchid\Layouts\Shop\ShopOrderLayout;
use app\Orchid\Layouts\Shop\ShopVoucherLayout;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\Request;

class CustomerEditScreen extends Screen
{

    public $customer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ShopCustomer $customer): iterable
    {
        return [
            'customer' => $customer,
            'orders' => ShopOrders::where('customer_id', $customer->customer_id)->paginate(),
            'vouchers' => ShopVouchers::where('customer_id', $customer->customer_id)->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit customer account.';
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
                ->method('save')
                ->type(Color::SUCCESS)
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
            Layout::rows([
                Group::make([
                    Input::make('customer.username')
                        ->title('Username')
                        ->value('@' . $this->customer->username)
                        ->disabled(),

                    Input::make('customer.first_name')
                        ->title('First Name')
                        ->disabled(),

                    Input::make('customer.middle_name')
                        ->title('Middle Name')
                        ->disabled(),

                    Input::make('customer.last_name')
                        ->title('Last Name')
                        ->disabled(),
                ])->alignStart(),

                Group::make([
                    Input::make('customer.phone')
                        ->title('Phone Number')
                        ->disabled(),
                    Input::make('customer.email')
                        ->title('Email')
                        ->disabled()
                ])->alignCenter(),

                Group::make([
                    Input::make('customer.email_verified')
                        ->title('Email verified')
                        ->disabled(),
                    Input::make('customer.phone_verified')
                        ->title('Phone verified')
                        ->disabled()
                ])->alignEnd()
            ]),

            ShopOrderLayout::class,
            ShopVoucherLayout::class
        ];
    }

    /**
     * Saves the customer data from the request and redirects to the customers page.
     *
     * @param \Illuminate\Http\Request $request The request object containing the customer data.
     * @return RedirectResponse The redirect response to the customers page.
     */
    public function save(Request $request): RedirectResponse
    {
        $this->customer->fill($request->get('customer'))->save();

        Toast::info("You saved " . $this->customer->username . ' account');

        event(new UpdateAudit('customer', 'Updated ' . $this->customer->username . ' account', \Illuminate\Support\Facades\Auth::user()->name));

        return redirect()->route('platform.shop.customers');
    }
}
