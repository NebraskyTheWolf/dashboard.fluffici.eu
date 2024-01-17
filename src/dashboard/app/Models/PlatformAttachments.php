<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformAttachments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_id',
        'attachment_id',
        'bucket'
    ];
}
