<?php

namespace App\Console\Commands;

use App\Models\ShopSales;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-sales';

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
        $sales = ShopSales::paginate();
        foreach ($sales as $sale) {
            if ($sale->deleted_at !== null) {
                if (Carbon::parse($sale->deleted_at)->isPast()) {
                    $sale->update(
                        [
                            'reduction' => 0
                        ]
                    );
                }
            }
        }
    }
}
