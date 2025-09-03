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
        Schema::table('warehouse_stock_logs', function (Blueprint $table) {
            //
             $table->after('sales_order_id', function (Blueprint $table) {
                $table->string('customer_id')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_stock_logs', function (Blueprint $table) {
            //
        });
    }
};
