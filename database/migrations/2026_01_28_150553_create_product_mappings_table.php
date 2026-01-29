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
        Schema::create('product_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('portal_code')->nullable();
            $table->string('item_code')->nullable();
            $table->string('basic_rate')->nullable();
            $table->string('net_landing_rate')->nullable();
            $table->timestamps();

            $table->unique(['sku', 'portal_code', 'item_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_mappings');
    }
};
