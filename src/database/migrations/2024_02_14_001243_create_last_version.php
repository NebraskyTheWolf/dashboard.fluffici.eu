<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('last_version', function (Blueprint $table) {
            $table->id();

            $table->integer('major')->default(1);
            $table->integer('minor')->default(0);
            $table->integer('patch')->default(0);

            $table->string('last_commit_id')->nullable();
            $table->string('current_commit_id')->nullable();

            $table->timestamps();
        });


        DB::table('last_version')->insert([
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('last_version');
    }
};
