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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('gstin')->nullable();
            $table->string('gst_treatment')->nullable();
            $table->string('pan')->nullable();
            $table->string('private_details')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('0: Inactive, 1: Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
