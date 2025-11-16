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
        Schema::create('warehouse_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_order_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('sku');
            $table->integer('allocated_quantity')->default(0);
            $table->integer('sequence')->default(1)->comment('Allocation sequence: 1 for first warehouse, 2 for second, etc.');
            $table->enum('status', ['pending', 'allocated', 'fulfilled', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['sales_order_id', 'sku']);
            $table->index(['warehouse_id', 'sku']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_allocations');
    }
};
