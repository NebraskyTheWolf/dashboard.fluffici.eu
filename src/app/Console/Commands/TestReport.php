<?php

namespace App\Console\Commands;

use App\Models\ReportedAttachments;
use Illuminate\Console\Command;

class TestReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-report';

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
        $report = new ReportedAttachments();
        $report->username = "Vakea";
        $report->email = "vakea@fluffici.eu";
        $report->reason = "This is a test report.";
        $report->isLegalPurpose = true;
        $report->attachment_id = "ystmRycjgISxQse2DtDbZ0O9UD7IIs5QomOCbQHwPd";
        $report->save();

        printf('Content Report sent.');
    }
}
