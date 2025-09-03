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
        Schema::create('sales_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('temp_order_id')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('vendor_code')->nullable();
            $table->integer('ordered_quantity')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('warehouse_stock_id')->nullable();
            $table->string('sku')->nullable();  // sku from the vendor
            $table->decimal('price', 10, 2)->nullable(); // snapshot of price at order time
            $table->decimal('subtotal', 12, 2)->nullable(); // qty * price
            
            // $table->enum('customer_status', ['available', 'unavailable'])->nullable()->default('available');
            // $table->enum('vendor_status', ['available', 'unavailable'])->nullable()->default('available');
            // $table->enum('sku_status', ['available', 'unavailable'])->nullable()->default('available');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_products');
    }
};
