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

            // General
            $table->boolean('enabled')->default(0);
            $table->string('favicon')->nullable();
            $table->string('banner')->nullable();

            $table->string('email')->nullable();
            $table->string('return_policy')->nullable();

            // Features
            $table->boolean('shop_vouchers')->default(0);
            $table->boolean('shop_sales')->default(0);

            $table->string('shop_billing')->default(0);
            $table->string('billing_host')->nullable();
            $table->string('billing_secret')->nullable();

            // Maintenance
            $table->boolean('shop_maintenance')->default(0);
            $table->boolean('shop_maintenance-text')->default(0);

            // Payment
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
