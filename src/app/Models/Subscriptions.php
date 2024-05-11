<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    public $table = 'subscriptions';

    public $fillable = [
        'user_id',
        'is_subscribed'
    ];
}
