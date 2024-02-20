<?php

namespace App\Events;

use App\Models\Events;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AkceUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $akce;

    /**
     * Create a new event instance.
     */
    public function __construct(Events $akce)
    {
        $this->akce = $akce;
    }
}
