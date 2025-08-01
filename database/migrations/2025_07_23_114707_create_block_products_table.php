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
        Schema::create('block_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_stock_id')->constrained()->onDelete('cascade');
            $table->string('sales_order_id')->nullable();
            $table->string('purchase_order_id')->nullable();
            $table->string('block_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_products');
    }
};
