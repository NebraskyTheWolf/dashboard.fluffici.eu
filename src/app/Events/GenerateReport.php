<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class GenerateReport
 *
 * The GenerateReport class is responsible for generating reports.
 */
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
