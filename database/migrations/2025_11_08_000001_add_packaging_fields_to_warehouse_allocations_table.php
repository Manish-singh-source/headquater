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
            $table->integer('final_dispatched_quantity')->nullable()->after('allocated_quantity')->comment('Final quantity dispatched after packaging');
            $table->integer('box_count')->nullable()->after('final_dispatched_quantity')->comment('Number of boxes for this warehouse allocation');
            $table->integer('weight')->nullable()->after('box_count')->comment('Weight for this warehouse allocation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            $table->dropColumn(['final_dispatched_quantity', 'box_count', 'weight']);
        });
    }
};

