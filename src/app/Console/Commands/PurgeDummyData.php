<?php

namespace App\Console\Commands;

use App\Models\AuditLogs;
use App\Models\Event\Events;
use App\Models\Shop\Customer\Order\OrderedProduct;
use App\Models\Shop\Customer\Order\OrderIdentifiers;
use App\Models\Shop\Customer\Order\OrderInvoice;
use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\Shop\Customer\Order\ShopOrders;
use App\Models\Shop\Customer\ShopVouchers;
use App\Models\Shop\Internal\ShopCategories;
use App\Models\Shop\Internal\ShopProducts;
use Illuminate\Console\Command;

class PurgeDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (env('ALLOW_DUMMY_SUPPRESSOR', false)) {

            $identifiers = OrderIdentifiers::all();
            $orders = ShopOrders::all();
            $products = ShopProducts::all();
            $orderProducts = OrderedProduct::all();
            $categories = ShopCategories::all();
            $payments = OrderPayment::all();
            $vouchers = ShopVouchers::all();
            $events = Events::all();
            $audits = AuditLogs::all();
            $invoice = OrderInvoice::all();

            $deletedValue = 0;

            foreach ($identifiers as $identifier) {
                $identifier->delete();

                $deletedValue++;
            }

            foreach ($orders as $order) {
                $order->delete();

                $deletedValue++;
            }

            foreach ($products as $prd) {
                $prd->delete();

                $deletedValue++;
            }

            foreach ($orderProducts as $prdele) {
                $prdele->delete();

                $deletedValue++;
            }

            foreach ($categories as $cat) {
                $cat->delete();

                $deletedValue++;
            }

            foreach ($payments as $payment) {
                $payment->delete();

                $deletedValue++;
            }

            foreach ($vouchers as $voucher) {
                $voucher->delete();

                $deletedValue++;
            }

            foreach ($audits as $audit) {
                $audit->delete();
                $deletedValue++;
            }

            foreach ($invoice as $invi) {
                $invi->delete();

                $deletedValue++;
            }

            foreach ($events as $event) {
                $event->delete();

                $deletedValue++;
            }

            $audit = new AuditLogs();
            $audit->name = "System";
            $audit->type = "SYSTEM_RESET";
            $audit->slug = "All dummy data has been purged from CLI.";
            $audit->save();

            printf($deletedValue . " data has been removed.");

        } else {
            printf('This operation is not allowed on production');
        }
    }
}
