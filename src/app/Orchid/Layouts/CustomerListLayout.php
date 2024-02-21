<?php

namespace App\Orchid\Layouts;

use App\Models\ShopCustomer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CustomerListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'customers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('first_name', 'First Name')
                ->render(function (ShopCustomer $customer) {
                    return Link::make($customer->first_name)
                        ->route('platform.shop.customer.edit', $customer)
                        ->icon('bs.pencil');
                }),
            TD::make('last_name', 'Last Name'),
            TD::make('username', 'Username')
                ->render(function (ShopCustomer $customer) {
                    return '@' . $customer->username;
                }),
            TD::make('email', 'Email'),
            TD::make('account_status', 'Account Status'),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.person-dash-fil';
    }

    protected function textNotFound(): string
    {
        return 'There is no customer(s) at the moment.';
    }
}
