<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ReportedAttachments extends Model
{
    use AsSource;
    public $fillable = [
        'type',
        'messages',
        'username',
        'email',
        'reason',
        'isLegalPurpose',
        'attachment_id'
    ];

    public $casts = [
        'messages' => 'string'
    ];
}
