<?php


namespace App\Models;

use DASPRiD\Enum\Exception\IllegalArgumentException;
use Exception;
use InvalidArgumentException;
use Nette\Schema\ValidationException;
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
    public function calculate(int $min, int $max): int
    {
        if ($min <= 0 || $max <= 0)
            return 0;
        return $min * ($max / 100);
    }

    /**
     * Retrieves a product based on the specified UPC-A code.
     *
     * The method first validates the provided UPC-A code and returns null if it is invalid.
     * Then, it removes the check digit from the UPC-A and extracts the product ID.
     * Finally, it fetches the product with the matching ID and returns it.
     *
     * @param string $upc The UPC-A code of the product.
     * @return ShopProducts|null The fetched product or null if no product is found.
     *
     * @throws InvalidArgumentException If the provided UPC-A is invalid.
     */
    public function getProductFromUpcA(string $upc): ?ShopProducts
    {
        // Check if UPC-A is valid
        if (!$this->isValidUPCA($upc)) {
            return null;
        }

        // Remove the check digit
        $productId = substr($upc, 0, -1);

        // Fetch the product by ID
        return ShopProducts::find($productId);
    }

    /**
     * Check if a UPC-A (Universal Product Code) is valid.
     *
     * This method validates a given UPC-A by performing a series of checks on its structure and checksum.
     * A valid UPC-A must have a total of 12 digits.
     * It calculates the sum of the digits at odd and even positions separately.
     * The total sum is multiplied by 3 for the odd digits and added to the sum of even digits.
     * The result is then subtracted from the nearest greater multiple of 10.
     * If the calculated checksum is equal to the last digit of the UPC-A, it is considered valid.
     *
     * @param string $upc The UPC-A string to be validated.
     * @return bool True if the UPC-A is valid, false otherwise.
     */
    public function isValidUPCA(string $upc): bool
    {
        if (strlen($upc) != 12) {
            return false;
        }

        $oddSum = 0;
        $evenSum = 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i % 2 == 0) {
                $oddSum .= $upc[$i];
            } else {
                $evenSum .= $upc[$i];
            }
        }

        $totalSum = $evenSum + (3 * $oddSum);
        $checksum = 10 - ($totalSum % 10);

        return $checksum == $upc[11];
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
     * Generate a UPC-A code for the product.
     *
     * This method generates a unique UPC-A code for the product based on its ID.
     * The code is generated by padding the product ID with zeros to a total length of 12 digits.
     * The digits are then used to calculate the checksum according to the UPC-A algorithm.
     * The checksum is appended to the padded product ID to form the final UPC-A code.
     *
     * @return string The generated UPC-A code for the product.
     * @throws ValidationException If the generated UPC-A code is not 12 digits long.
     *
     * @throws IllegalArgumentException If the product ID is not set.
     */
    public function generateUPCA(): string
    {
        // throw an exception if id is not set
        if ($this->id === null) {
            throw new IllegalArgumentException("Product ID is required");
        }

        $code = str_pad($this->id, 12, '0', STR_PAD_LEFT);
        $oddSum = 0;
        $evenSum = 0;
        for ($i = 0; $i < 11; $i++) {
            if ($i % 2 == 0) {
                $oddSum += $code[$i];
            } else {
                $evenSum += $code[$i];
            }
        }
        $totalSum = $oddSum + (3 * $evenSum);
        $checksum = 10 - ($totalSum % 10) % 10;
        $upca = $code . $checksum;

        // validate if the length of UPC-A code is not 12
        if(strlen($upca) != 12){
            throw new ValidationException("UPC-A code should be 12 digits long");
        }

        return $upca;
    }
}
