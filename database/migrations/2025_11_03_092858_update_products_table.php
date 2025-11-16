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
        Schema::table('products', function (Blueprint $table) {
            // Drop existing unique index on sku
            $table->dropUnique('products_sku_unique');

            // Add composite unique index
            $table->unique(['warehouse_id', 'sku'], 'warehouse_sku_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop composite unique index
            $table->dropUnique('warehouse_sku_unique');

            // Re-add unique on sku only (rollback)
            $table->unique('sku', 'products_sku_unique');
        });
    }
};
