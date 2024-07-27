<?php

namespace App\Console\Commands;

use App\Models\Shop\Customer\Order\OrderInvoice;
use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\Shop\Customer\Order\ShopOrders;
use App\Models\Shop\Internal\ShopSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
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
     * - Generates a unique invoice id
     * - Retrieves order, orderIdentifier, carrier, and products
     * - Iterates over products, calculating tax, discount, and sub-total
     * - Creates a PDF from a view with the calculated and fetched data
     * - Enables remote and javascript options for the PDF
     * - Renders and saves the PDF
     * - Creates and invoice object and saves it.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        $orderId = $this->option('orderId');

        if (empty($orderId)) {
            printf("OrderId is missing.");
            return;
        }

        $invoiceId = Str::upper(Str::substr(Uuid::uuid4()->toString(), 0, 8));
        $today = Carbon::today()->format("Y-m-d");

        $order = ShopOrders::where('order_id', $orderId)
            ->firstOrFail();
        $orderIdentifier = $order->identifiers;
        $carrier = $order->carrier->carrierPrice;

        $products = $order->orderedProducts;

        /**
         * Aha, tak tady máme úžasnou ukázku kódu, že?
         * Symfonie komplexity, což elegantně tančí mezi světy zdravého rozumu a absurdity!
         * Podívejte, jak statečně se funkce reduce snaží zkrotit nezkrotnou smečku dat.
         * A jaké to je radostné rozlušťování toho jejího složitého tance proměnných a metodických volání!
         * Je to takové jako naučit kočku trikům – zábavné, ale nakonec docela marné, že?
         * Opravdový klenot softwarového inženýrství, pokud máte rádi trochu masochismu.
         * No do prdele, to je kousek!
         *
         * -Vakea
         */
        $calculations = $products->reduce(function ($carry, $product) {
            $prdele = $product->product;

            $carry['subTotal'] += $prdele->getNormalizedPrice();
            $carry['salePercentage'] += $prdele->getProductSale();
            $carry['totalDiscount'] += $prdele->calculate($prdele->price, $prdele->getProductSale());
            $carry['taxPercentage'] +=  $prdele->getProductTax();
            $carry['totalTax'] += $prdele->calculate($prdele->price, $prdele->getProductTax());

            return $carry;
        }, ['subTotal' => 0, 'salePercentage' => 0, 'totalDiscount' => 0, 'taxPercentage' => 0, 'totalTax' => 0]);

        $payment = OrderPayment::where('order_id', $orderId)->get();
        $providers = $payment->pluck('provider')->implode(', ');
        $paymentTotalPrice = $payment->sum('price');

        $settings = ShopSettings::latest()->first();

        $document = Pdf::loadView('documents.invoice', [
            'issuedAt' => $today,
            'invoiceId' => $invoiceId,
            'orderId' => $orderIdentifier->public_identifier,

            'contact_address' => $settings->email,
            'first_name' => $order->customer->first_name,
            'last_name' => $order->customer->last_name,
            'address_one' => $order->address->address_one,
            'address_two' => $order->address->address_two ?: "",
            'country' => $order->address->country,
            'email' => $order->customer->email,
            'products' => $products,

            'paymentMethod' => $providers,
            'paymentPrice' => $paymentTotalPrice,

            'subTotal' => number_format($calculations['subTotal'] - $calculations['totalTax'] + $calculations['totalDiscount'] - $carrier),
            'discountPer' => number_format($calculations['salePercentage']),
            'grandTotal' => number_format($calculations['subTotal']),
            'discount' => number_format($calculations['totalDiscount']),
            'taxPer' => number_format($calculations['taxPercentage']),
            'tax' => number_format($calculations['totalTax']),

            'carrierPrice' => number_format($carrier),

            'returnPolicy' => $settings->return_policy
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
