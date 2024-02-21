<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the necessary tables for the shop module.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::connection('shop')->table('shop_orders', function (Blueprint $table) {
            $table->string('customer_id')->references('customer_id')->on('shop_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_customer');
    }
};
