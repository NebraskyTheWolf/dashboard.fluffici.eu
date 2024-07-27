<?php

namespace App\Console\Commands;

use App\Models\Security\Auth\PasswordRecovery;
use App\Models\Security\Auth\UserOtp;
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
            if (Carbon::parse($auth->expiry)->isPast()) {
                $auth->delete();
            }
        }

        $recovery = PasswordRecovery::paginate();
        foreach ($recovery as $item) {
            if (Carbon::parse($item->recovery_expiration)->isPast()) {
                $item->delete();
            }
        }
    }
}
