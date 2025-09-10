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
            $table->string('facility_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('gstin')->nullable();
            $table->string('gst_treatment')->nullable();
            $table->string('pan')->nullable();
            $table->string('private_details')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('0: Inactive, 1: Active');
            $table->string('billing_address')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_zip')->nullable();
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
