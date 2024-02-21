<?php

namespace App\Console\Commands;

use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderIdentifiers;
use App\Models\OrderInvoice;
use App\Models\OrderPayment;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class Invoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:invoice {--orderId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * This function generates and saves an invoice for a given order.
     * The process is as follows:
     * - Retrieves the orderId from command
     * - Generates a unique invoice Id
     * - Retrieves order, orderIdentifier, carrier, and products
     * - Iterates over products, calculating tax, discount, and sub-total
     * - Creates a PDF from a view with the calculated and fetched data
     * - Enables remote and javascript options for the PDF
     * - Renders and saves the PDF
     * - Creates and invoice object and saves it.
     *
     * @return void
     */
    public function handle(): void
    {
        $orderId = $this->option('orderId');
        $invoiceId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

        $today = Carbon::today()->format("Y-m-d");

        $order = ShopOrders::where('order_id', $orderId)->first();
        $orderIdentifier = OrderIdentifiers::where('order_id', $orderId)->first();
        $carrier = OrderCarrier::where('order_id', $orderId)->first();
        $products = OrderedProduct::where('order_id', $orderId)->paginate();

        $taxPercentage = 0;
        $salePercentage = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $subTotal = 0;
        foreach ($products as $product) {
            $prd = ShopProducts::where('id', $product->product_id)->first();
            $subTotal += $prd->getNormalizedPrice();
            $salePercentage += $prd->getProductSale();
            $totalDiscount += $prd->calculate($prd->price, $prd->getProductSale());
            $taxPercentage +=  $prd->getProductTax();
            $totalTax += $prd->calculate($prd->price, $prd->getProductTax());
        }

        $payment = OrderPayment::where('id', $product->id)->first();

        $document = Pdf::loadView('documents.invoice', [
            'invoiceId' => $invoiceId,
            'orderId' => $orderIdentifier->public_identifier,

            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'address_one' => $order->first_address,
            'address_two' => $order->second_address ?: "",
            'country' => $order->country,
            'email' => $order->email,

            'products' => $products,

            'paymentMethod' => $payment->provider,
            'paymentPrice' => $payment->price,

            'subTotal' => number_format($subTotal - $totalTax + $totalDiscount - $carrier->price),
            'discountPer' => number_format($salePercentage),
            'discount' => number_format($totalDiscount),
            'taxPer' => number_format($taxPercentage),
            'tax' => number_format($totalTax),
            'carrierPrice' => number_format($carrier->price),

            'grandTotal' => number_format($subTotal),
        ]);

        $document->getOptions()->setIsRemoteEnabled(true);
        $document->getOptions()->setIsJavascriptEnabled(true);

        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");

        $document->render();
        $filename = 'invoice-' . $today . '-' . $invoiceId . '.pdf';

        $document->save($filename, 'public');

        $invoice = new OrderInvoice();
        $invoice->order_id = $orderIdentifier->internal;
        $invoice->report_id = $invoiceId;
        $invoice->customer_id = $order->customer_id;
        $invoice->attachment_id =  $filename;
        $invoice->save();
    }
}
