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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('name');
            $table->integer('item_id');
            $table->string('vendor_name')->nullable();
            $table->string('entity_vendor_legal_name')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('facility_name')->nullable();
            $table->string('units')->nullable();
            $table->integer('units_ordered')->nullable();
            $table->double('landing_rate', 10, 2)->nullable();
            $table->double('cost_price', 10, 2)->nullable();
            $table->double('total_amount', 10, 2)->nullable();
            $table->double('mrp', 10, 2)->nullable();
            $table->string('po_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
