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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('phone');
            $table->string('email')->unique();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('address');
            $table->string('state');
            $table->string('city');
            $table->integer('pin_code');
            $table->integer('account_no');
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
