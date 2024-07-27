<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EventAttachments extends Model
{
    use AsSource;

    public $table = 'event_attachments';

    public $fillable = [
        'user_id',
        'event_id',
        'published',
        'attachment_id',
    ];
}
