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
            if (! Schema::hasColumn('sales_order_products', 'packaging_data_state')) {
                $table->string('packaging_data_state')
                    ->default('draft')
                    ->after('product_status')
                    ->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_products', function (Blueprint $table) {
            if (Schema::hasColumn('sales_order_products', 'packaging_data_state')) {
                $table->dropColumn('packaging_data_state');
            }
        });
    }
};

