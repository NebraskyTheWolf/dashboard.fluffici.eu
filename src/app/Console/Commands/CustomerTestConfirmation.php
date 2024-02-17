<?php

namespace App\Console\Commands;

use App\Mail\CustomerOrderConfirmed;
use App\Models\OrderedProduct;
use App\Models\OrderIdentifiers;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Random\RandomException;

class CustomerTestConfirmation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:customer-test-confirmation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Handles the process of creating a new order in the shop.
     *
     * This method performs the following steps:
     * 1. Creates a new ShopOrders instance.
     * 2. Generates a unique order ID using UUID version 4.
     * 3. Sets the customer information for the order.
     * 4. Saves the order to the database.
     * 5. Creates a new OrderedProduct instance.
     * 6. Sets the product information for the ordered product.
     * 7. Saves the ordered product to the database.
     * 8. Creates a new OrderIdentifiers instance.
     * 9. Sets the order identifiers information.
     * 10. Generates a random numeric access pin using the `generateNumericToken` method.
     * 11. Saves the order identifiers to the database.
     * 12. Sends a customer order confirmation email using the `CustomerOrderConfirmed` mail class.
     *
     * @throws RandomException If an error occurs while generating the numeric token.
     * @throws Exception
     */
    public function handle(): void
    {
        $order = new ShopOrders();
        $order->order_id = Uuid::uuid4();
        $order->first_name = "John";
        $order->last_name = "Smith";
        $order->phone_number = "+420 607 100 100";
        $order->email = "alex.leroy8303@gmail.com";
        $order->first_address = "5 valley of foxes";
        $order->second_address = "18 avenue of cookies";
        $order->postal_code = "190-FAOP";
        $order->country = "cs";
        $order->status = 'OUTING';
        $order->save();

        $orderPrd = new OrderedProduct();
        $orderPrd->order_id = $order->order_id;
        $orderPrd->product_id = ShopProducts::latest()->first()->id;
        $orderPrd->product_name = "Dergi mug";
        $orderPrd->price = 190;
        $orderPrd->save();

        $orderIdentifier = new OrderIdentifiers();
        $orderIdentifier->order_id = $order->order_id;
        $orderIdentifier->public_identifier = $this->generateLatestID();
        $orderIdentifier->internal = Uuid::uuid4();
        $orderIdentifier->access_pin = $this->generateNumericToken();
        $orderIdentifier->save();

        Mail::to($order->email)->send(new CustomerOrderConfirmed($order, $orderIdentifier));
    }

    /**
     * Generates the latest ID for a shop order.
     *
     * This method retrieves the latest shop order record and generates
     * an ID based on the current year and the padded ID of the record.
     *
     * @return string The generated latest ID for the shop order.
     * @throws Exception If there is an error retrieving the latest shop order record.
     */
    private function generateLatestID(): string
    {
        $record = ShopOrders::latest()->first();
        return date('Y'). '-'. str_pad($record->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generates a random numeric token consisting of 4 digits.
     *
     * @return string The generated numeric token.
     * @throws RandomException
     */
    private function generateNumericToken(): string
    {
        $token = "";
        for ($count = 0; $count < 4; $count++) {
            $token .= random_int(0, 9);
        }
        return $token;
    }
}
