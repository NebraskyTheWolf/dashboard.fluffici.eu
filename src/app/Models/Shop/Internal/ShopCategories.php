<?php

namespace App\Models\Shop\Internal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class ShopCategories extends Model
{
    use AsSource;
    public $connection = 'shop';
    protected $fillable = [
        'name',
        'order',
        'displayed',
        'deleted_at'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(ShopProducts::class);
    }
}
