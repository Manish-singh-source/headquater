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
            $table->enum('shipping_status', ['ready_to_ship', 'shipped', 'delivered', 'completed'])
                ->default('ready_to_ship')
                ->after('product_status')
                ->comment('Shipping status for this warehouse allocation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            $table->dropColumn('shipping_status');
        });
    }
};

