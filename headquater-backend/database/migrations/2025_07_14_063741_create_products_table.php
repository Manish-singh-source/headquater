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
            $table->string('ean_code');
            $table->string('brand');
            $table->string('brand_title');
            $table->string('mrp');
            $table->string('category');
            $table->string('pcs_set');
            $table->string('sets_ctn');
            $table->string('vendor_name');
            $table->string('vendor_purchase_rate');
            $table->string('gst');
            $table->string('vendor_net_landing');
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
