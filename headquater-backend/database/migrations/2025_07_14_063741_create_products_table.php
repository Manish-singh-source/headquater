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
            $table->string('sku')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('item_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('entity_vendor_legal_name')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('facility_name')->nullable();
            $table->string('units')->nullable();
            $table->string('units_ordered')->nullable();
            $table->string('landing_rate')->nullable();
            $table->string('cost_price')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('mrp')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('Active : 1,Inactive : 0');
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
