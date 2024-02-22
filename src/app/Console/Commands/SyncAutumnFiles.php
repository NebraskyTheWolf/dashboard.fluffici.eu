<?php

namespace App\Console\Commands;

use app\Models\AutumnFile;
use App\Models\PlatformAttachments;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class SyncAutumnFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-autumn-files';

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
        $files = AutumnFile::all();

        foreach ($files as $file) {
            if ($file->deleted)
                continue;

            $platform = PlatformAttachments::where('attachment_id', $file->_id)->where('bucket', $file->tag);
            if (!$platform->exists()) {
                $newSync = new PlatformAttachments();
                $newSync->attachment_id = $file->_id;
                $newSync->bucket = $file->tag;
                $newSync->user_id = 1;
                $newSync->action_id = Uuid::uuid4();
                $newSync->save();
            }
        }
    }
}
