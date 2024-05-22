<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE websocket_apps MODIFY max_backend_events_per_sec INT;');
        DB::statement('ALTER TABLE websocket_apps MODIFY max_client_events_per_sec INT;');
        DB::statement('ALTER TABLE websocket_apps MODIFY max_read_req_per_sec INT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void { }
};
