<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('akce')->create('events_interesteds', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->references('event_id')->on('events');
            $table->string('username');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('akce')->dropIfExists('events_interesteds');
    }
};
