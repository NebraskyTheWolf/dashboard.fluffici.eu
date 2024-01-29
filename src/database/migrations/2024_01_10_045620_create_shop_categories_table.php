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
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('order')->unsigned();
            $table->boolean('displayed')->default(0);
            $table->dateTime('deleted_at')->nullable(); // expire date
            $table->timestamps();
        });

        Schema::create('shop_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->text('description');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('shop_categories');
            $table->float('price');
            $table->boolean('displayed')->default(0);
            $table->string('image_path')->nullable()->default(null);
            $table->dateTime('deleted_at')->nullable(); // expire date
            $table->timestamps();
        });

        Schema::create('shop_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->nullable()->default(null);
            $table->string('product_type', 8); // ITEM or CATEGORY
            $table->float('reduction'); // percentage
            $table->dateTime('deleted_at')->nullable(); // expire date
            $table->timestamps();
        });

        Schema::create('shop_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->float('money');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_categories');
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_sales');
        Schema::dropIfExists('shop_vouchers');
        Schema::dropIfExists('shop_orders');
    }
};
