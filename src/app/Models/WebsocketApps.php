<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class WebsocketApps extends Model
{
    use AsSource;

    protected $table = 'websocket_apps';
    protected $fillable = [
        'application_name',
        'id',
        'key',
        'secret',

        'enabled',
        'max_connections',
        'enable_client_messages',
        'max_backend_events_per_sec',
        'max_client_events_per_sec',
        'max_read_req_per_sec',

        'webhooks',

        'max_presence_members_per_channel',
        'max_presence_member_size_in_kb',
        'max_channel_name_length',
        'max_event_channels_at_once',

        'max_event_name_length',
        'max_event_payload_in_kb',
        'max_event_batch_size',
        'enable_user_authentication'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'enable_client_messages' => 'boolean',
        'max_connections' => 'integer',
        'max_backend_events_per_sec' => 'integer',
        'max_client_events_per_sec' => 'integer',
        'max_read_req_per_sec' => 'integer',

        'max_presence_members_per_channel' => 'integer',
        'max_presence_member_size_in_kb' => 'integer',
        'max_channel_name_length' => 'integer',
        'max_event_channels_at_once' => 'integer',

        'max_event_name_length' => 'integer',
        'max_event_payload_in_kb' => 'integer',
        'max_event_batch_size' => 'integer',
        'enable_user_authentication' => 'integer',
    ];
}
