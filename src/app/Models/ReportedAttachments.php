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
        'message',
        'username',
        'email',
        'reason',
        'isLegalPurpose',
        'attachment_id'
    ];
}
