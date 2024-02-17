<?php


namespace App\Models;

use InvalidArgumentException;
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

    /**
     * Retrieve the tax percentage for the product.
     *
     * This method queries the ProductTax and TaxGroup models to find the tax percentage for the product with the specified ID.
     * If a tax percentage exists, the percentage value is returned as an integer.
     * If no tax percentage exists, 0 is returned.
     *
     * @return int The tax percentage for the product, or 0 if no tax percentage exists.
     */
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

    /**
     * Retrieve the sale reduction value for the product.
     *
     * This method queries the ShopSales model to find the sale reduction value for the product with the specified ID.
     * If a sale reduction exists, the reduction value is returned as an integer.
     * If no sale reduction exists, 0 is returned.
     *
     * @return int The sale reduction value for the product, or 0 if no reduction exists.
     */
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
     * Retrieves a product based on the specified EAN code.
     *
     * The method first validates the provided EAN code and throws an exception if it is invalid.
     * Then, it removes the check digit from the EAN and extracts the product ID.
     * Finally, it fetches the product with the matching ID and returns it.
     *
     * @param string $ean The EAN code of the product.
     * @return ShopProducts|null The fetched product or null if no product is found.
     *
     * @throws InvalidArgumentException If the provided EAN is invalid.
     */
    public function getProductFromEan(string $ean): ?ShopProducts
    {
        // Check if EAN is valid
        if (!$this->isValidEan($ean)) {
            return null;
        }

        // Remove the check digit
        $productId = substr($ean, 0, -1);

        // Fetch the product by ID
        // Here Product::find is a placeholder, replace it with actual product fetching code.
        return ShopProducts::find($productId);
    }

    public function getProductFromEanDBG(string $ean): string
    {
        // Check if EAN is valid
        if (!$this->isValidEan($ean)) {
            return "Not Valid EAN-13 format.";
        }

        // Remove the check digit
        // Fetch the product by ID
        return substr($ean, 0, -1);
    }

    /**
     * Checks if the given EAN code is valid.
     *
     * The method calculates the check digit of the EAN code and checks if the calculated digit is a valid check digit.
     * If the calculated digit is a valid check digit, the method returns true, otherwise it returns false.
     *
     * @param string $ean The EAN code to be validated.
     * @return bool True if the EAN code is valid, false otherwise.
     */
    private function isValidEan(string $ean): bool
    {
        $check = 0;
        for ($i = 0; $i < 13; $i += 2) {
            $check .= substr($ean, $i, 1);
        }
        for ($i = 1; $i < 12; $i += 2) {
            $check += 3 * substr($ean, $i, 1);
        }

        return ($check % 10 === 0);
    }

    public function getAvailableProducts(): int
    {
        return ProductInventory::where('product_id', $this->id)->first()->available ?: 0;
    }

    public function incrementQuantity(): void
    {
        ProductInventory::where('product_id', $this->id)->first()->increment('available', 1);
    }

    public function decrementQuantity(): void
    {
        ProductInventory::where('product_id', $this->id)->first()->decrement('available', 1);
    }

    public function createOrGetInventory(): ProductInventory
    {
        $inventory = ProductInventory::where('product_id', $this->id);
        if ($inventory->exists()) {
            return $inventory->first();
        } else {
            $inventory = new ProductInventory();
            $inventory->product_id = $this->id;
            $inventory->available = 0;
            $inventory->save();
            return $inventory;
        }
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
