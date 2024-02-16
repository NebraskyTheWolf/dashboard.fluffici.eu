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
        Schema::create('device_authorization', function (Blueprint $table) {
            $table->id();

            $table->integer('linked_user')->references('id')->on('user');

            $table->string('deviceId');
            $table->string('status');

            $table->boolean('restricted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_authorization');
    }
};
