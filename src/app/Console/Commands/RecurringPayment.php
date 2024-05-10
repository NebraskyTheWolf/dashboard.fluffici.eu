<?php

namespace app\Console\Commands;

use App\Models\Accounting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecurringPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(): void {
        $payments = Accounting::all();

        foreach ($payments as $payment) {
            if ($payment->is_recurring) {
                if (Carbon::parse($payment->recurring_at)->isPast()) {
                    $recurringPayment = new Accounting();
                    $recurringPayment->type = $payment->type;
                    $recurringPayment->source = $payment->source . ' (Recurring)';
                    $recurringPayment->amount = $payment->amount;
                    $recurringPayment->is_recurring = false;
                    $recurringPayment->save();
                }
            }
        }
    }
}
