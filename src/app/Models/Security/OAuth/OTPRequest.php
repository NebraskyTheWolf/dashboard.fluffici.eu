<?php

namespace App\Models\Security\OAuth;

use Illuminate\Database\Eloquent\Model;

class OTPRequest extends Model
{
    protected $table = 'otp_request_logger';
}
