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
        Schema::create('vendor_p_i_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('vendor_code')->nullable();
            $table->string('vendor_sku_code')->nullable();
            $table->string('mrp')->nullable();
            $table->string('quantity_requirement')->nullable();
            $table->string('available_quantity')->nullable();
            $table->string('purchase_rate')->nullable();
            $table->string('gst')->nullable();
            $table->string('hsn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_p_i_s');
    }
};
