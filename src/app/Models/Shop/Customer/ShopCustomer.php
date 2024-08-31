<?php

namespace App\Models\Shop\Customer;

use App\Models\Shop\Customer\Order\ShopOrders;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ShopCustomer extends Model
{
    use AsSource;

    public $table = 'shop_customer';
    public $connection = 'shop';

    public $hidden = [
        'password'
    ];

    public function addresses() {
        return ShopCustomerAddress::where('customer_id', $this->customer_id);
    }

    public function orders() {
        return ShopOrders::where('customer_id', $this->customer_id);
    }

    public function vouchers() {
        return ShopVouchers::where('customer_id', $this->customer_id);
    }
}
