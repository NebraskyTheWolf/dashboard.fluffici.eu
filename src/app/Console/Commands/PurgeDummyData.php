<?php

namespace App\Console\Commands;

use App\Models\AuditLogs;
use App\Models\OrderedProduct;
use App\Models\OrderIdentifiers;
use App\Models\OrderPayment;
use App\Models\ShopCategories;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopVouchers;
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
    public function handle()
    {
        $identifiers = OrderIdentifiers::paginate();
        $orders = ShopOrders::paginate();
        $products = ShopProducts::paginate();
        $orderProducts = OrderedProduct::paginate();
        $categories = ShopCategories::paginate();
        $payments = OrderPayment::paginate();
        $vouchers = ShopVouchers::paginate();
        $audits = AuditLogs::paginate();

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

        $audit = new AuditLogs();
        $audit->name = "System";
        $audit->type = "SYSTEM_RESET";
        $audit->slug = "All dummies has been removed (" . $deletedValue . " deleted entries)";
        $audit->save();

        printTitle('All dummies was deleted.');
    }
}
