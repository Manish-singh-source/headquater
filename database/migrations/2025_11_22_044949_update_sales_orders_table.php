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
            //
            $table->enum('status', ['pending', 'blocked', 'ready_to_package', 'packaging', 'partial_packaged', 'all_packaged', 'packaged', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled'])->default('blocked')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'blocked', 'ready_to_package', 'packaging', 'partial_packaged', 'packaged', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled'])->change();
        });
    }
};
