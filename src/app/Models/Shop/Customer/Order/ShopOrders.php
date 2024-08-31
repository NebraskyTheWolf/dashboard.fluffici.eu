<?php

namespace App\Models\Shop\Customer\Order;

use App\Models\Shop\Customer\ShopCustomer;
use App\Models\Shop\Customer\ShopCustomerAddress;
use App\Models\Shop\Internal\ShopCarriers;
use App\Models\Shop\Internal\ShopSales;
use Illuminate\Database\Eloquent\Model;
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
    public function orderedProducts() {
        return OrderedProduct::where('order_id',$this->order_id);
    }

    public function payments() {
        return OrderPayment::where('order_id',$this->order_id);
    }

    public function identifiers() {
        return OrderIdentifiers::where('order_id',$this->order_id)->first();
    }

    public function invoice() {
        return OrderInvoice::where('order_id',$this->order_id)->first();
    }

    public function getTotalPrice(): float {
        $totalPrice = 0;
        foreach ($this->orderedProducts()->get() as $orderedProduct) {
            $totalPrice += $orderedProduct->price * $orderedProduct->quantity;
        }
        return $totalPrice;
    }

    public function getFormattedTotalPrice(): string
    {
        return number_format($this->getTotalPrice(), 2);
    }

    public function customer() {
        return ShopCustomer::where('customer_id', $this->customer_id)->first();
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function carrier() {
        return ShopCarriers::where('id', $this->carrier_id)->first();
    }

    public function sales() {
        return ShopSales::where('id', $this->sale_id)->first();
    }

    public function address()
    {
        return ShopCustomerAddress::where('id', $this->address_id)->first();
    }
}
