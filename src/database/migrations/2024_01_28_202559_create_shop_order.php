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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('first_address');
            $table->string('second_address')->nullable();
            $table->string('postal_code');
            $table->string('country');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('status')->default("PROCESSING");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
