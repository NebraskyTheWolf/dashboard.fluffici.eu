<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceAuthorization extends Model
{
    public $table = 'device_authorization';

    public $fillable = [
        'linked_user',
        'deviceId'
    ];

}
