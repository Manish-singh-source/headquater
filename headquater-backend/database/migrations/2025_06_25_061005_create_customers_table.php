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
            $table->string('company_name', 70);
            $table->string('gst_number', 255);
            $table->string('pan_number', 255);

            $table->string('shipping_address', 255);
            $table->integer('shipping_country');
            $table->integer('shipping_state');
            $table->integer('shipping_city');
            $table->integer('shipping_pincode');

            $table->integer('billing_address'); // This might be `string` if not ID
            $table->integer('billing_country');
            $table->integer('billing_state');
            $table->integer('billing_city');
            $table->integer('billing_pincode');

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
