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
        Schema::connection('shop')->create('product_tax', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->references('id')->on('shop_products');
            $table->integer('tax_id')->references('id')->on('tax_group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('shop')->dropIfExists('product_tax');
    }
};
