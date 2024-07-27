<?php

namespace App\Console\Commands;

use App\Events\AkceUpdate;
use App\Models\Event\Events;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinishOldEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:finish-old-event';

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
        $events = Events::paginate();
        foreach ($events as $event) {
            if ($event->end != null
                && ($event->status !== "ENDED")
                && ($event->status !== "CANCELLED"))
            {
                if (Carbon::parse($event->end)->isPast()) {
                    $copy = $event;
                    $event->update(
                        [
                            'status' => 'ENDED'
                        ]
                    );

                    event(new AkceUpdate($event, $copy));
                }
            }
        }
    }
}
