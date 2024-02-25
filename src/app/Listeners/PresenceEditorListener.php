<?php

namespace App\Listeners;

use App\Events\PresenceEditor;

class PresenceEditorListener
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PresenceEditor $event): void
    {
        broadcast($event);
    }
}
