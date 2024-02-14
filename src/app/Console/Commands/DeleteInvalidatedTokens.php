<?php

namespace App\Console\Commands;

use App\Models\PasswordRecovery;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteInvalidatedTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-invalidated-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Handle the request.
     *
     * @return void
     */
    public function handle()
    {
        $otp = UserOtp::paginate();

        foreach ($otp as $auth) {
            if (Carbon::parse($auth->created_at)->addMinutes(30)->isPast()) {
                $auth->delete();
            }
        }
    }
}
