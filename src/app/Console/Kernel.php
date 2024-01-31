<?php

namespace App\Console;

use App\Console\Commands\DeleteOldProducts;
use App\Console\Commands\DeleteOldSales;
use App\Console\Commands\FinishOldEvent;
use App\Console\Commands\GenerateMonthlyReport;
use App\Console\Commands\Refresh;
use App\Console\Commands\StartNewEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void{
        $schedule->command(GenerateMonthlyReport::class)->monthly();
        $schedule->command(Refresh::class)->daily();
        $schedule->command(DeleteOldProducts::class)->everyTenSeconds();
        $schedule->command(DeleteOldSales::class)->everyTenSeconds();
        $schedule->command(FinishOldEvent::class)->everyTenSeconds();
        $schedule->command(StartNewEvent::class)->everyTenSeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
