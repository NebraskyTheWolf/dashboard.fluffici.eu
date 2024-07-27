<?php

namespace app\Models\Shop\Customer\Order;

use app\Models\Shop\Customer\ShopCustomer;
use app\Models\Shop\Customer\ShopCustomerAddress;
use app\Models\Shop\Internal\ShopCarriers;
use app\Models\Shop\Internal\ShopSales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class ShopOrders extends Model
{
    use AsSource, Chartable;
    public $connection = 'shop';
    protected $fillable = [
        'sale_id',
        'carrier_id',
        'address_id',
        'customer_id',
        'status',
        'tracking_number'
    ];

    /**
     * Returns the ordered products related to this entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderedProducts(): HasMany {
        return $this->hasMany(OrderedProduct::class);
    }

    public function payments(): BelongsTo {
        return $this->belongsTo(OrderPayment::class);
    }

    public function identifiers(): BelongsTo {
        return $this->belongsTo(OrderIdentifiers::class);
    }

    public function invoice(): BelongsTo {
        return $this->belongsTo(OrderInvoice::class);
    }

    public function getTotalPrice(): float {
        $totalPrice = 0;
        foreach ($this->orderedProducts() as $orderedProduct) {
            $totalPrice += $orderedProduct->price * $orderedProduct->quantity;
        }
        return $totalPrice;
    }

    public function getFormattedTotalPrice(): string
    {
        return number_format($this->getTotalPrice(), 2);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(ShopCustomer::class);
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShopCarriers::class);
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(ShopSales::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(ShopCustomerAddress::class);
    }
}
