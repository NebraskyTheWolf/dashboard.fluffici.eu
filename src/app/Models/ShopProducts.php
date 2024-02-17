<?php


namespace App\Models;

use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopProducts extends Model
{
    use AsSource, Chartable;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'displayed',
        'image_path',
        'deleted_at'
    ];

    /**
     * Returns the URL of the image.
     *
     * If the image path is not null, it returns the URL based on the image path.
     * Otherwise, it returns a default URL.
     *
     * @return string The URL of the image.
     */
    public function getImage(): string
    {
        if ($this->image_path !== null) {
            return env('AUTUMN_HOST', 'https://autumn.fluffici.eu/attachments/') . $this->image_path;
        } else {
            return env('AUTUMN_HOST', 'https://autumn.fluffici.eu/attachments/') . '90don8HDCYvvsg0vamKGeTMJReNCGXKsGDE5PJXfOR';
        }
    }

    public function getProductTax(): int
    {
        $productTax = ProductTax::where('product_id', $this->id);
        if ($productTax->exists()) {
            $group = $productTax->first();
            $tax = TaxGroup::where('id', $group->tax_id)->first();

            return $tax->percentage;
        }

        return 0;
    }

    public function getProductSale(): int
    {
        $productSale = ShopSales::where('product_id', $this->id);
        if ($productSale->exists()) {
            $group = $productSale->first();

            return $group->reduction;
        }

        return 0;
    }

    /**
     * Calculate the normalized price of the product.
     *
     * Returns the normalized price of the product by calculating the price including tax and subtracting any sale discounts.
     * If the price of the product is less than or equal to 0, the normalized price will be 0.
     *
     * @return int The normalized price of the product.
     */
    public function getNormalizedPrice(): int
    {
        if ($this->price <= 0)
            return 0;

        $tax = $this->calculate($this->price, $this->getProductTax());
        $sale = $this->calculate($this->price, $this->getProductSale());

        return ($this->price + $tax) - $sale;
    }

    /**
     * Calculate the value based on the given minimum and maximum values.
     *
     * Returns the calculated value by multiplying the minimum value with the ratio between the maximum value and 100.
     * If the minimum or maximum values are less than or equal to 0, the calculated value will be 0.
     *
     * @param int $min The minimum value.
     * @param int $max The maximum value.
     * @return int The calculated value.
     */
    public function calculate($min, $max): int
    {
        if ($min <= 0 || $max <= 0)
            return 0;
        return $min * ($max / 100);
    }

    /**
     * Generates a valid EAN-13 barcode.
     *
     * The barcode is generated based on the ID of the object.
     * It uses the Luhn algorithm to calculate the checksum digit.
     *
     * @return string The generated EAN-13 barcode.
     */
    public function generateEAN13(): string
    {
        $code = str_pad($this->id, 12, '0', STR_PAD_LEFT);

        $sum = 0;
        foreach (str_split(strrev($code)) as $index => $digit) {
            $sum += $digit * (3 - 2 * ($index % 2));
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return $code . $checksum;
    }
}
