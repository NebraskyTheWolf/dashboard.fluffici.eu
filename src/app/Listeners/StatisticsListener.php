<?php

namespace App\Listeners;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Statistics;

class StatisticsListener 
{
    use InteractsWithQueue;

    public function handle(Statistics $event) {
        broadcast(new Channel('statistics'));
    }
}
