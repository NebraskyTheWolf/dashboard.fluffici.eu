<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

class ReminderTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminder-test';

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
        $event = new Events();
        $event->name = "Test";
        $event->descriptions = "Test";
        $event->begin = Carbon::today();
        $event->end = Carbon::today()->addDays(30);
        $event->status = "STARTED";
        $event->type = "PHYSICAL";
        $event->min = "{\"lat\": \"\", \"lng\":\"\"}";
        $event->max = "{\"lat\": \"\", \"lng\":\"\"}";
        $event->city = "Praha";
        $event->link = "https://fox-around.com";
        // No need to save.
        // $event->save();

        Mail::to('vakea@fluffici.eu')->send(new ReminderMail($event, User::where('email', 'vakea@fluffici.eu')->first()));

        printf("Mail sent.");
    }
}
