<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This class is made to report attachment from the main website.

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reported_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('reason');
            $table->boolean('isLegalPurpose')->default(false);
            $table->string('attachment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reported_attachments');
    }
};
