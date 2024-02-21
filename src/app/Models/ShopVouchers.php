<?php

namespace App\Models;

use Carbon\Carbon;
use InvalidArgumentException;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

class ShopVouchers extends Model
{
    use AsSource, Chartable;
    public $connection = 'shop';
    protected $fillable = [
        'code',
        'money'
    ];


    /**
     * Retrieves the expiration date in the specified format.
     *
     * @return string The expiration date formatted as "Y-m-d".
     */
    public function getExpiration(): string
    {
        return Carbon::parse($this->expiration)->format("Y-m-d");
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
}
