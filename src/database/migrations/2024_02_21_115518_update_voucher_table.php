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
        Schema::connection('shop')->table('shop_vouchers', function (Blueprint $table) {
            $table->string('customer_id')->references('customer_id')->on('shop_customer');

            $table->timestamp('expiration')->nullable();

            $table->boolean('restricted')->default(false);
            $table->boolean('gift')->default(false);
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
