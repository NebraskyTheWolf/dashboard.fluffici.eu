<?php

namespace App\Console\Commands;

use App\Mail\CustomerOrderConfirmed;
use App\Models\OrderedProduct;
use App\Models\ShopOrders;
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
        $order->email = "vakea@fluffici.eu";
        $order->first_address = "5 valley of foxes";
        $order->second_address = "18 avenue of cookies";
        $order->postal_code = "190-FAOP";
        $order->country = "cs";
        $order->status = 'OUTING';
        $order->save();

        $orderPrd = new OrderedProduct();
        $orderPrd->order_id = $order->order_id;
        $orderPrd->product_id = 5;
        $orderPrd->product_name = "Dergi mug";
        $orderPrd->price = 190;
        $orderPrd->save();

        Mail::to($order->email)->send(new CustomerOrderConfirmed($order));
    }
}
