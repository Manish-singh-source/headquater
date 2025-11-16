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
            $table->enum('product_status', ['pending', 'packaging', 'partially_packaged', 'approval_pending', 'packaged', 'completed', 'cancelled'])->nullable()->default('pending')->after('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            //
            $table->dropColumn('product_status');
        });
    }
};
