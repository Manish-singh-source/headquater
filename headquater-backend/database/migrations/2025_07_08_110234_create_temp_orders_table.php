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
        Schema::create('temp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('customer_name')->nullable();
            $table->string('po_number')->nullable();
            $table->string('sku')->nullable();
            $table->string('facility_name')->nullable();
            $table->string('facility_location')->nullable();
            $table->string('po_date')->nullable();
            $table->string('po_expiry_date')->nullable();
            $table->string('hsn')->nullable();
            $table->string('item_code')->nullable();
            $table->string('description')->nullable();
            $table->string('basic_rate')->nullable();
            $table->string('gst')->nullable();
            $table->string('net_landing_rate')->nullable();
            $table->string('mrp')->nullable();
            $table->string('po_qty')->nullable();
            $table->string('available_quantity')->nullable();
            $table->string('unavailable_quantity')->nullable();
            $table->string('block')->nullable();
            $table->string('rate_confirmation')->nullable();
            $table->string('case_pack_quantity')->nullable();
            $table->string('purchase_order_quantity')->nullable();
            $table->string('vendor_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_orders');
    }
};
