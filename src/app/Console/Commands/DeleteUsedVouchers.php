<?php

namespace App\Console\Commands;

use App\Models\ShopVouchers;
use Illuminate\Console\Command;

class DeleteUsedVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-used-vouchers';

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

    }
}
