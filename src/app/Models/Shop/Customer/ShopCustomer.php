<?php

namespace App\Models\Shop\Customer;

use app\Models\Shop\Customer\Order\ShopOrders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class ShopCustomer extends Model
{
    use AsSource;

    public $table = 'shop_customer';
    public $connection = 'shop';

    public $hidden = [
        'password'
    ];

    public function addresses(): HasMany {
        return $this->hasMany(ShopCustomerAddress::class);
    }

    public function orders(): HasMany {
        return $this->hasMany(ShopOrders::class);
    }

    public function vouchers(): HasMany {
        return $this->hasMany(ShopVouchers::class);
    }
}
