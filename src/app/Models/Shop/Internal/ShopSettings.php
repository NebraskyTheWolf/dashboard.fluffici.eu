<?php

namespace App\Models\Shop\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSettings extends Model
{
    use HasFactory;
    public $connection = 'shop';
    public $fillable = [
        'enabled',
        'favicon',
        'banner',
        'email',
        'return_policy',
        'shop_vouchers',
        'shop_sales',
        'shop_billing',
        'billing_host',
        'billing_secret',
        'shop_maintenance',
        'shop_maintenance_text',
        'gateway_secret',
        'gateway_key',
    ];

    protected $casts = [
        'enabled'  => 'boolean',
        'shop_vouchers'  => 'boolean',
        'shop_sales'  => 'boolean',
        'shop_billing'  => 'boolean',
    ];
}
