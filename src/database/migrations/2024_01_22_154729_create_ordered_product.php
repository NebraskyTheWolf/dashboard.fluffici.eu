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
        Schema::create('order_carrier', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders');
            $table->string('carrier_name');
            $table->bigInteger('price')->default(0);
            $table->timestamps();
        });

        Schema::create('order_sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders')->unique();
            $table->string('sale_id')->references('id')->on('shop_sales');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordered_product');
    }
};
