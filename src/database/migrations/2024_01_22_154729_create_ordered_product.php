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
        Schema::create('ordered_product', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders');
            $table->integer('product_id')->references('id')->on('shop_products');
            $table->integer('product_name')->references('name')->on('shop_products');
            $table->float('price')->references('price')->on('shop_products');
            $table->bigInteger('quantity')->default(1);
            $table->timestamps();
        });

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

        Schema::create('order_payment', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders')->unique();
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
        Schema::dropIfExists('ordered_product');
    }
};
