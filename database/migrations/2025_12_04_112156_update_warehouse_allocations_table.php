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
            $table->double('box_count')->nullable()->after('final_dispatched_quantity')->change();
            $table->double('weight')->nullable()->after('box_count')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            //
            $table->integer('box_count')->default(0)->after('final_dispatched_quantity');
            $table->integer('weight')->default(0)->after('box_count');
        });
    }
};
