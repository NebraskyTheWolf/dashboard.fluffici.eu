<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class GenerateReport
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }
}
