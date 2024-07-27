<?php

namespace App\Models\Shop\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class ShopCustomerAddress extends Model
{
    use AsSource;
    public $table = 'shop_customer_address';
    public $connection = 'shop';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ShopCustomer::class);
    }
}
