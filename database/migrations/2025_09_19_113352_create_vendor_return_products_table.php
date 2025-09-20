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
        Schema::create('vendor_return_products', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_pi_product_id')->nullable();
            $table->integer('return_quantity')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('return_description')->nullable();
            $table->enum('return_status', ['pending', 'on_the_way', 'returned', 'accepted', 'completed'])->nullable()->default('pending')->comment('pending, accept, on the way, returned, completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_return_products');
    }
};
