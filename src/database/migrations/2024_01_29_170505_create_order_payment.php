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
        Schema::connection('shop')->create('order_payment', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders');
            $table->string('status');
            $table->string('transaction_id');
            $table->string('provider');
            $table->string('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('shop')->dropIfExists('order_payment');
    }
};
