<?php

namespace App\Models\Shop\Customer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class ShopVouchers extends Model
{
    use AsSource, Chartable;
    public $connection = 'shop';
    protected $fillable = [
        'code',
        'money',
        'customer_id',
        'gift',
        'expiration',
        'restricted',
        'note'
    ];


    /**
     * Retrieves the expiration date and time as a formatted string.
     *
     * @return string The expiration date and time formatted as "Y-m-d at H:i:s".
     */
    public function getExpiration(): string
    {
        $date = Carbon::parse($this->expiration);
        return $date->format("Y-m-d") . ' at ' . $date->format("H:i:s");
    }

    /**
     * Checks if the object has expired.
     *
     * @return bool Returns true if the object has expired, false otherwise.
     * @throws InvalidArgumentException if the $expiration date is not a valid date string.
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expiration)->isPast();
    }

    /**
     * Checks if the object is restricted.
     *
     * @return bool Returns true if the object is restricted, false otherwise.
     */
    public function isRestricted(): bool
    {
        return $this->restricted;
    }

    /**
     * Scope the query to only include active records.
     *
     * @param mixed $query The query instance.
     * @return mixed The modified query instance.
     */
    public function scopeActive(mixed $query): ShopVouchers
    {
        return $query->where('expiration', '>', Carbon::now())->first();
    }

    /**
     * Check if the given $customerId is assigned to the current instance of the object.
     *
     * @param string $customerId The customer ID to check against.
     *
     * @return bool Returns true if the given $customerId is assigned to the current instance, false otherwise.
     */
    public function isCustomerAssigned(string $customerId): bool
    {
        return ($this->customer_id === $customerId);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ShopCustomer::class);
    }
}
