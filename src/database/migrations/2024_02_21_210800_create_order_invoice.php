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
        Schema::connection('shop')->create('order_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('report_id');
            $table->string('customer_id');
            $table->string('attachment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('shop')->dropIfExists('order_invoice');
    }
};
