<?php

namespace App\Models;

use Carbon\Carbon;
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


    public function getExpiration(): string
    {
        return Carbon::parse($this->expiration)->format("Y-m-d");
    }

    public function isExpired(): bool
    {
        return Carbon::parse($this->expiration)->isPast();
    }

    public function isRestricted(): bool
    {
        return $this->restricted;
    }

    public function scopeActive($query)
    {
        return $query->where('expiration', '>', Carbon::now());
    }
}
