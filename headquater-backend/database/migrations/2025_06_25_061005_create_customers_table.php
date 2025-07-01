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
            $table->id(); // id INT PRIMARY KEY AUTO_INCREMENT

            $table->string('first_name', 70);
            $table->string('last_name', 70);
            $table->string('email', 70);
            $table->string('phone', 70);
            $table->string('company_name', 70)->nullable();
            $table->string('gst_number', 255)->nullable();
            $table->string('pan_number', 255)->nullable();

            $table->string('shipping_address', 255)->nullable();
            $table->integer('shipping_country')->nullable();
            $table->integer('shipping_state')->nullable();
            $table->integer('shipping_city')->nullable();
            $table->integer('shipping_pincode')->nullable();

            $table->integer('billing_address')->nullable(); // This might be `string` if not ID
            $table->integer('billing_country')->nullable();
            $table->integer('billing_state')->nullable();
            $table->integer('billing_city')->nullable();
            $table->integer('billing_pincode')->nullable();

            $table->enum('status', ['0', '1'])->default('1')->comment('Active : 1,Inactive : 0');

            // Optional: add created_at & updated_at
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
