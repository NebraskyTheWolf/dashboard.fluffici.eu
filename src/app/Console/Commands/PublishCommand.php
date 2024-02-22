<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fluffici:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the Orchid resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag'   => 'orchid-assets',
            '--force' => true,
        ]);
    }
}
