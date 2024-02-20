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
use Carbon\Carbon;
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
        if (env('ALLOW_DUMMY_SUPPRESSOR', false)) {

            $identifiers = OrderIdentifiers::all();
            $orders = ShopOrders::all();
            $products = ShopProducts::all();
            $orderProducts = OrderedProduct::all();
            $categories = ShopCategories::all();
            $payments = OrderPayment::all();
            $vouchers = ShopVouchers::all();
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
                if ($audit->name === "Vakea" && Carbon::parse($audit->created_at)->addDays(30)->isPast()) {
                    $audit->delete();
                }

                $deletedValue++;
            }

            $audit = new AuditLogs();
            $audit->name = "System";
            $audit->type = "SYSTEM_RESET";
            $audit->slug = "All dummies has been removed (" . $deletedValue . " deleted entries)";
            $audit->save();

        } else {
            printf('This operation is not allowed on production');
        }
    }
}
