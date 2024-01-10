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

            // Payment method modules

            $table->boolean('paypal')->default(0);
            $table->boolean('paysafecard')->default(0);
            $table->boolean('stripe')->default(0);
            $table->boolean('gopay')->default(0);

            // Payment method information
            // PAYPAL
            $table->string('paypal_payout_email')->nullable();

            // Stripe
            $table->string('stripe_api_secret')->nullable();
            $table->string('stripe_api_key')->nullable();
            $table->boolean('stripe_test_mode')->default(0);
            
            // GoPay 
            

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
