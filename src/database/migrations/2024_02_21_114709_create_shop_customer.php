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
        Schema::dropIfExists('shop_customer');
        Schema::connection('shop')->create('shop_customer', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');

            $table->string('username');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('phone')->nullable();
            $table->string('email');

            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->boolean('sms_verified')->default(false);

            $table->string('password', 255);
            $table->boolean('isMFAEnabled')->default(false);
            $table->string('MFASecret')->nullable();
            $table->string('account_status')->default("Inactive");

            $table->timestamps();
        });

        Schema::dropIfExists('shop_customer_limit');
        Schema::connection('shop')->create('shop_customer_limit', function (Blueprint $table) {
            $table->id();

            $table->string('customer_id')->references('customer_id')->on('shop_customer');
            $table->string('product_id')->references('id')->on('shop_products');
            $table->integer('purchased');

            $table->timestamps();
        });

        Schema::dropIfExists('shop_customer_address');
        Schema::connection('shop')->create('shop_customer_address', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->references('customer_id')->on('shop_customer');
            $table->string('address_one');
            $table->string('address_two');
            $table->string('city');
            $table->string('zip');
            $table->string('country');

            $table->string('type')->default('delivery');
            $table->boolean('primary');

            $table->timestamps();
        });

        Schema::dropIfExists('shop_customer_contact');
        Schema::connection('shop')->create('shop_customer_contact', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->references('customer_id')->on('shop_customer');
            $table->string('customer_id');
            $table->boolean('email');
            $table->boolean('phone');
            $table->boolean('sms');

            $table->timestamps();
        });

        Schema::dropIfExists('shop_customer_contract');
        Schema::connection('shop')->create('shop_customer_contract', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->references('customer_id')->on('shop_customer');
            $table->string('customer_id');
            $table->string('customer_ip');

            $table->boolean('gdpr');
            $table->boolean('return_policy');
            $table->boolean('tos');

            $table->timestamps();
        });

        Schema::dropIfExists('shop_customer_terminated');
        Schema::connection('shop')->create('shop_customer_terminated', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->references('customer_id')->on('shop_customer');
            $table->string('customer_id');

            $table->string('reason')->nullable();

            $table->timestamps();
        });

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
