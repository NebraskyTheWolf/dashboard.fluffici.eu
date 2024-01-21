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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('album_id');
            $table->string('name');
            $table->string('description')->nullable();
            // If the banner is empty we take the first picture inside the album!
            $table->string('bannerId')->nullable();
            $table->boolean('public');

            $table->string('objects');
            $table->string('authors');

            $table->bigInteger('views');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
