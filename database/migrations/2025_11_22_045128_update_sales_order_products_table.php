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
        Schema::table('sales_order_products', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'packaging', 'packaged', 'approval_pending', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_products', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'packaging', 'packaged', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled'])->change();
        });
    }
};
