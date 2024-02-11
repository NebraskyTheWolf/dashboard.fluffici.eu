<?php

namespace App\Console\Commands;

use App\Mail\WeeklyStatistic;
use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;

class StatisticsMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:statistics-mail';

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
        $users = User::paginate();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new WeeklyStatistic());
        }
    }
}
