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
            $table->integer('final_final_dispatched_quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_products', function (Blueprint $table) {
            //
            $table->decimal('final_final_dispatched_quantity', 15, 2)->nullable()->change();
        });
    }
};
