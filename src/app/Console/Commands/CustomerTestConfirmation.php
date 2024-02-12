<?php

namespace App\Console\Commands;

use App\Mail\CustomerOrderConfirmed;
use App\Models\OrderedProduct;
use App\Models\OrderIdentifiers;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

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
     * Execute the console command.
     */
    public function handle()
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

    private function generateLatestID(): string
    {
        $record = ShopOrders::latest()->first();
        return date('Y'). '-'. str_pad($record->id, 6, '0', STR_PAD_LEFT);
    }

    private function generateNumericToken(): string
    {
        $i = 0;
        $token = "";

        while ($i < 4) {
            $token .= random_int(0, 9);
            $i++;
        }

        return $token;
    }
}
