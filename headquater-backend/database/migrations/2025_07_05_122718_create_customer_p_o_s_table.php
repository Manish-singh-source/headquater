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
            $table->string('customer');
            $table->string('po_number');
            $table->string('facility_name');
            $table->string('facility_location');
            $table->string('po_date');
            $table->string('po_expiry_date');
            $table->string('HSN');
            $table->string('item_code');
            $table->string('description');
            $table->string('basic_rate');
            $table->string('net_landing_rate');
            $table->string('mrp');
            $table->string('rate_confirmation');
            $table->string('po_qty');
            $table->string('case_pack_qty');
            $table->string('available');
            $table->string('unavailable');
            $table->string('block');
            $table->string('purchase_order_qty');
            $table->string('vendor_code');
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
