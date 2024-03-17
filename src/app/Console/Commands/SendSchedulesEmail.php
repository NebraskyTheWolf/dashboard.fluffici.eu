<?php

namespace App\Console\Commands;

use App\Mail\ScheduleMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

class SendSchedulesEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-schedules-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Handles sending schedule emails to users with access to the "platform.systems.events" permission.
     *
     * @return void
     */
    public function handle(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasAccess('platform.systems.events')) {
                Mail::to($user->email)
                    ->locale($user->getLanguage())
                    ->send(new ScheduleMail());
            }
        }
    }
}
