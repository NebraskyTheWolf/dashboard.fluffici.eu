<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class DeviceAuthorization extends Model
{
    use AsSource;

    public $table = 'device_authorization';

    public $fillable = [
        'linked_user',
        'deviceId',
        'restricted',
        'status'
    ];

}
