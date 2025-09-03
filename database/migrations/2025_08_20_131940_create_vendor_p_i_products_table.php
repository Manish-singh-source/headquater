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
        Schema::create('vendor_p_i_products', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_id')->nullable();
            $table->string('vendor_pi_id')->nullable();
            $table->string('vendor_sku_code')->nullable();
            $table->string('mrp')->nullable();
            $table->string('quantity_requirement')->nullable();
            $table->string('available_quantity')->nullable();
            $table->string('quantity_received')->nullable()->default(0);
            $table->string('purchase_rate')->nullable();
            $table->string('gst')->nullable();
            $table->string('hsn')->nullable();
            $table->string('issue_item')->default(0);
            $table->string('issue_reason')->nullable();
            $table->string('issue_description')->nullable();
            $table->enum('issue_status', ['pending', 'return', 'accept', 'completed'])->nullable()->default('pending')->comment('pending, return, accept,completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_p_i_products');
    }
};
