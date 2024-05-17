<?php

namespace App\Console\Commands;

use App\Events\AkceUpdate;
use App\Models\Events;
use App\Models\ShopProducts;
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
            if ($event->end != null) {
                if (Carbon::parse($event->end)->isPast()) {
                    $event->update(
                        [
                            'status' => 'ENDED'
                        ]
                    );

                    event(new AkceUpdate($event));
                }
            }
        }
    }
}
