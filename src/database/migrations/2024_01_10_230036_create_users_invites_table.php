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
        Schema::create('users_invites', function (Blueprint $table) {
            $table->id();
            $table->string('invite_id');
            $table->string('invited_by')->references('id')->on('users');
            $table->string('role')->references('slug')->on('roles');
            $table->string('username');
            $table->string('email');
            $table->string('status')->default('PENDING');
            $table->datetime('expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_invites');
    }
};
