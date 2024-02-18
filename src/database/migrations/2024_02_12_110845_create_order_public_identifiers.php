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
        Schema::connection('shop')->create('order_public_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->references('order_id')->on('shop_orders');
            $table->string('public_identifier');
            $table->string('internal');
            $table->string('access_pin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('shop')->dropIfExists('order_public_identifiers');
    }
};
