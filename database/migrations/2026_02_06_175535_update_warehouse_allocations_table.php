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
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            //
            $table->string('customer_id')->nullable()->after('sales_order_product_id')->comment('Customer id for the allocation.');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            //
            $table->dropColumn('customer_id');
        });
    }
};
