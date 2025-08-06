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
        Schema::create('warehouse_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_id')->nullable();
            $table->string('purchase_order_id')->nullable();    
            $table->string('sales_order_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('stock')->default(0)->nullable();
            $table->string('block_quantity')->default(0)->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_logs');
    }
};
