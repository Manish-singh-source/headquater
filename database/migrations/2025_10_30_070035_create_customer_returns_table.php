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
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->integer('return_quantity')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('return_description')->nullable();
            $table->enum('return_status', ['pending', 'accept', 'on_the_way', 'returned', 'completed'])->nullable()->default('pending')->comment('pending, accept, on the way, returned, completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_returns');
    }
};
