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
            $table->string('ean_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('brand_title')->nullable();
            $table->string('mrp')->nullable();
            $table->string('category')->nullable();
            $table->string('pcs_set')->nullable()->default('1');
            $table->string('sets_ctn')->nullable()->default('1');
            $table->string('vendor_name')->nullable();
            $table->string('vendor_purchase_rate')->nullable();
            $table->string('gst')->nullable();
            $table->string('vendor_net_landing')->nullable();
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
