<?php

namespace App\Listeners;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UpdateAudit;
use App\Models\AuditLogs;
use App\Events\Statistics;

class AuditListener 
{
    use InteractsWithQueue;

    public function handle(UpdateAudit $event) {
        AuditLogs::create([
            'name' => $event->username,
            'slug' => $event->slug,
            'type' => $event->type
        ]);

        event(new Statistics());

        broadcast(new Channel('audit-update'));
    }
}
