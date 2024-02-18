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
        Schema::connection('akce')->create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->string('name');
            $table->text('descriptions');
            $table->datetime('begin');
            $table->datetime('end');
            $table->string('status')->default('INCOMING');

            // The type can be
            // Physical
            // Online
            $table->string('type');

            // To use a Map Integration and make a highlighted zone.
            // Using mappy.cz api or another.
            $table->string('min')->nullable();
            $table->string('max')->nullable();

            $table->string('city')->nullable();
            $table->string('link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('akce')->dropIfExists('events');
    }
};
