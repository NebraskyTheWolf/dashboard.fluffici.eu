<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Events\UpdateAudit;
use App\Models\AuditLogs;
use App\Events\Statistics;

class AuditListener
{
    use InteractsWithQueue;

    public function handle(UpdateAudit $event) {
        $audit = new AuditLogs();
        $audit->name = $event->username;
        $audit->slug = $event->slug;
        $audit->type = $event->type;
        $audit->save();

        event(new Statistics());
    }
}
