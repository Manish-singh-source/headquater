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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('sales_order_id')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('customer_group_id')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('vendor_code')->nullable();
            $table->double('total_amount')->nullable()->default(0);
            $table->double('total_paid_amount')->nullable()->default(0);
            $table->double('total_due_amount')->nullable()->default(0);
            $table->enum('status', ['pending', 'completed', 'received', 'ready_to_ship', 'ready_to_package'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
