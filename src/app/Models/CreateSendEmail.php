<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class CreateSendEmail extends Model
{
    use AsSource;

    public $fillable = [
        'to',
        'email',
        'content'
    ];

    public $table = 'send_email';
}
