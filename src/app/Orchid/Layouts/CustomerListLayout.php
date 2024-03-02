<?php

namespace App\Orchid\Layouts;

use App\Models\ShopCustomer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CustomerListLayout extends Table
{
    /**
     * Zdroj dat.
     *
     * Jméno klíče, z kterého se získává z dotazu.
     * Výsledky, které budou prvky tabulky.
     *
     * @var string
     */
    protected $target = 'customers';

    /**
     * Získejte buňky tabulky, které se mají zobrazit.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('first_name', 'Křestní jméno')
                ->render(function (ShopCustomer $customer) {
                    return Link::make($customer->first_name)
                        ->route('platform.shop.customer.edit', $customer)
                        ->icon('bs.pencil');
                }),
            TD::make('last_name', 'Příjmení'),
            TD::make('username', 'Uživatelské jméno')
                ->render(function (ShopCustomer $customer) {
                    return '@' . $customer->username;
                }),
            TD::make('email', 'E-mail'),
            TD::make('account_status', 'Stav účtu'),
        ];
    }

    protected function iconNotFound(): string
    {
        return 'bs.person-dash-fil';
    }

    protected function textNotFound(): string
    {
        return 'V současné době nejsou žádní zákazníci.';
    }
}
