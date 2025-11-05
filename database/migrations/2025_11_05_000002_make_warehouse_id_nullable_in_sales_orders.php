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
        Schema::table('sales_orders', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['warehouse_id']);
            
            // Make warehouse_id nullable
            $table->foreignId('warehouse_id')->nullable()->change();
            
            // Re-add foreign key constraint
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['warehouse_id']);
            
            // Make warehouse_id NOT NULL again
            $table->foreignId('warehouse_id')->nullable(false)->change();
            
            // Re-add foreign key constraint
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }
};

