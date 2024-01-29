<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSettings extends Model
{
    use HasFactory;

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
        'shop_maintenance-text',
        'gateway_secret',
        'gateway_key',
    ];
}
