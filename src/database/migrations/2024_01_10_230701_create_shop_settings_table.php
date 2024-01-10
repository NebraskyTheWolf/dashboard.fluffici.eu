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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            
            // Shop Management settings
            $table->boolean('maintenance')->default(1);
            $table->boolean('vouchers')->default(0);
            $table->boolean('sales')->default(0);

            $table->string('gateway_secret')->nullable();
            $table->string('gateway_key')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
