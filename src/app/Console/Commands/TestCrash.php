<?php

namespace App\Console\Commands;

use App\Mail\ApplicationError;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Class TestCrash
 *
 * This class represents a console command that crashes intentionally for testing purposes.
 */
class TestCrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-crash';

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
        Mail::to('vakea@fluffici.eu')->send(new ApplicationError(
                "TestCrash.php",
                "This is a test",
                "35",
                "public function handle() {}")
        );
    }
}
