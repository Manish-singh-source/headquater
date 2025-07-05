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
        Schema::create('customer_p_o_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_group_id');
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('po_number')->nullable();
            $table->string('facility_name')->nullable();
            $table->string('facility_location')->nullable();
            $table->string('po_date')->nullable();
            $table->string('po_expiry_date')->nullable();
            $table->string('HSN')->nullable();
            $table->string('item_code')->nullable();
            $table->string('description')->nullable();
            $table->string('basic_rate')->nullable();
            $table->string('net_landing_rate')->nullable();
            $table->string('mrp')->nullable();
            $table->string('rate_confirmation')->nullable();
            $table->string('po_qty')->nullable();
            $table->string('case_pack_qty')->nullable();
            $table->string('available')->nullable();
            $table->string('unavailable')->nullable();
            $table->string('block')->nullable();
            $table->string('purchase_order_qty')->nullable();
            $table->string('vendor_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_p_o_s');
    }
};
