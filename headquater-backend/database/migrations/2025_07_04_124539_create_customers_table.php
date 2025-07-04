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
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->string('client_name');
            // contaxt name
            $table->string('contact_name');
            // contaxt email
            $table->string('contact_email')->nullable();
            // contaxt phone
            $table->string('contact_phone')->nullable();
            // billing address
            $table->string('billing_address')->nullable();
            // billing zip
            $table->string('billing_zip')->nullable();
            // billing city
            $table->string('billing_city')->nullable();
            // billing state
            $table->string('billing_state')->nullable();
            // billing country
            $table->string('billing_country')->nullable();
            // shipping address
            $table->string('shipping_address')->nullable();
            // shipping zip
            $table->string('shipping_zip')->nullable();
            // shipping city
            $table->string('shipping_city')->nullable();
            // shipping state
            $table->string('shipping_state')->nullable();
            // shipping country
            $table->string('shipping_country')->nullable();
            // gstin
            $table->string('gstin')->nullable();
            // pan
            $table->string('pan')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('Active : 1,Inactive : 0');
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
