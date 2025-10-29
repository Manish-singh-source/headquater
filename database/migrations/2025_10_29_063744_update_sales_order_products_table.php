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
            $table->integer('box_count')->nullable()->after('final_dispatched_quantity');
            $table->integer('weight')->nullable()->after('box_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_products', function (Blueprint $table) {
            //
            $table->dropColumn('box_count');
            $table->dropColumn('weight');
        });
    }
};
