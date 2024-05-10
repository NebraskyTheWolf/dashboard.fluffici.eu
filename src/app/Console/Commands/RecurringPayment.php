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
            // Check if the payment is recurring
            if ($payment->is_recurring) {
                // Parse the date and time at which the recurring payment should be made
                $recurringAt = Carbon::parse($payment->recurring_at);

                // Check if the current date is past the recurring payment's date
                // And the current day and month are the same as the recurring payment's day and month
                if (Carbon::now()->greaterThanOrEqualTo($recurringAt)
                    && Carbon::now()->day == $recurringAt->day
                    && Carbon::now()->month == $recurringAt->month) {

                    // Create and save the new recurring payment
                    $recurringPayment = new Accounting();
                    $recurringPayment->type = $payment->type;
                    $recurringPayment->source = $payment->source . ' (Recurring)';
                    $recurringPayment->amount = $payment->amount;
                    $recurringPayment->is_recurring = false;
                    $recurringPayment->save();

                    // Update the next recurring payment's date
                    $payment->recurring_at = $recurringAt->addMonth();
                    $payment->save();
                }
            }
        }
    }
}
