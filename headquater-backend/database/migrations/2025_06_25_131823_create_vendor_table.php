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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 70);
            $table->string('last_name', 70);
            $table->string('phone_number', 70);
            $table->string('email', 255);
            $table->string('gst_number', 255);
            $table->string('pan_number', 255);
            $table->text('address');
            $table->integer('state');
            $table->integer('city');
            $table->integer('country');
            $table->integer('pin_code');
            $table->string('bank_account_number', 255);
            $table->string('ifsc_number', 255);
            $table->string('bank_number', 70);
            $table->enum('status', ['0', '1'])->default('1')->comment('1 = active, 0 = inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
