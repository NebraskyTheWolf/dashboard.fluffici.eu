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
            $table->string('product_name')->references('name')->on('shop_products');
            $table->float('price')->references('price')->on('shop_products');
            $table->bigInteger('quantity')->default(1);
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
