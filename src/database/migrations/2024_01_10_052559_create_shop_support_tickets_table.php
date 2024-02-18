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
        Schema::connection('shop')->create('shop_support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            // The first message
            $table->string("message");
            // Gonna be calculated depending of the selected category
            // Example if it's a payment issue the priority will be 9
            $table->bigInteger("priority");
            $table->string('status')->default("PENDING");
            $table->string('order_id')->references('order_id')->on('shop_orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('shop')->dropIfExists('shop_support_tickets');
    }
};
