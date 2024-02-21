<?php


namespace App\Models;

use DASPRiD\Enum\Exception\IllegalArgumentException;
use InvalidArgumentException;
use Nette\Schema\ValidationException;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopProducts extends Model
{
    use AsSource, Chartable;
    public $connection = 'shop';
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
     * Retrieve the total tax percentage for the product.
     *
     * This method queries the ProductTax and TaxGroup models to find all the tax percentages for the product with the specified ID.
     * If tax percentages exist, the sum of all tax percentages is returned as an integer.
     * If no tax percentage exists, 0 is returned.
     *
     * @return int The total tax percentage for the product, or 0 if no tax percentage exists.
     */
    public function getProductTax(): int
    {
        $productTaxes = TaxGroup::all();
        $totalTax = 0;

        foreach ($productTaxes as $productTax) {
            $totalTax += $productTax->percentage;
        }

        return $totalTax;
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
     * Then, it extracts the product ID from the UPC-A by removing leading zeros and the automatically appended check digit.
     * Finally, it fetches the product with the matching ID and returns it.
     *
     * @param string $upc The UPC-A code of the product.
     * @return ShopProducts|null The fetched product or null if no product is found.
     *
     * @throws InvalidArgumentException If the provided UPC-A is invalid.
     */
    public function getProductFromUpcA(string $upc): ?ShopProducts
    {
        $upc = str_replace('0', '', substr($upc, 0, -1));

        $upc = intval($upc);

        return ShopProducts::find($upc);
    }

    public function getProductFromUpcADBG(string $upc): int
    {
        $upc = str_replace('0', '', $upc);
        $upc = substr($upc, 0, -1);
        $upc = intval($upc);

        return $upc;
    }

    /**
     * Retrieve the available quantity of the product.
     *
     * This method queries the ProductInventory model to find the available quantity of the product with the specified ID.
     * If an available quantity exists, the quantity value is returned as an integer.
     * If no available quantity exists, 0 is returned.
     *
     * @return int The available quantity of the product, or 0 if no quantity exists.
     */
    public function getAvailableProducts(): int
    {
        return ProductInventory::where('product_id', $this->id)->first()->available ?: 0;
    }

    /**
     * Increment the available quantity of the product by 1.
     *
     * This method fetches the product inventory using the ProductInventory model and finds the record with the specified product ID.
     * It then increments the 'available' column by 1 for that record.
     *
     * Note that the method does not return any value.
     */
    public function incrementQuantity(): void
    {
        ProductInventory::where('product_id', $this->id)->first()->increment('available');
    }

    /**
     * Decrement the quantity of the product.
     *
     * This method updates the available quantity of the product in the ProductInventory model by subtracting 1.
     *
     * @return void
     */
    public function decrementQuantity(): void
    {
        ProductInventory::where('product_id', $this->id)->first()->decrement('available');
    }

    /**
     * Creates a new ProductInventory for the current product or retrieves an existing one if already created.
     *
     * @return ProductInventory The created or retrieved ProductInventory.
     */
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

        $code = str_pad($this->id, 11, '0', STR_PAD_LEFT);
        $oddSum = 0;
        $evenSum = 0;
        for ($i = 0; $i < 11; $i++) {
            if (($i + 1) % 2 == 0) {
                $evenSum += $code[$i];
            } else {
                $oddSum += $code[$i];
            }
        }
        $totalSum = $evenSum + (3 * $oddSum);
        $checksum = (10 - ($totalSum % 10)) % 10;
        $upca = $code . $checksum;

        // validate if the length of UPC-A code is not 12
        if(strlen($upca) > 12){
            throw new ValidationException("UPC-A code should be 12 digits long ( " . strlen($upca) . ' found, checksum : ' . strlen($checksum) . ' / ' . $totalSum . ' <-!-> ' . $oddSum . ' == ' . $code . ' )');
        }

        return $upca;
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

        for ($i = 0; $i < 11; $i++) {
            if (($i + 1) % 2 == 0) {
                $evenSum .= $upc[ $i ];
            } else {
                $oddSum .= $upc[ $i ];
            }
        }

        $totalSum = $evenSum + (3 * $oddSum);
        $checksum = (10 - ($totalSum % 10));

        return $checksum == $upc[11];
    }

    /**
     * Retrieve the list of tax group of the product.
     *
     * This method queries the ProductTax model to find the tax groups for the product with the specified ID.
     * It constructs an associative array with the tax name as the key and the tax percentage as the value for each tax group.
     * If no tax groups exist, an empty array is returned.
     *
     * @return array The array with tax group names and their percentages for the product.
     */
    public function getTaxGroups(): array
    {
        $productTaxGroups = TaxGroup::all();
        $taxGroups = [];

        foreach ($productTaxGroups as $productTax) {
            $tax = TaxGroup::where('id', $productTax->tax_id)->first();

            if ($tax) {
                $taxGroups[$tax->name] = $tax->percentage;
            }
        }

        return $taxGroups;
    }

}
