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
            $table->enum('status', ['pending', 'blocked', 'ready_to_packaged', 'ready_to_ship', 'packaged', 'shipped', 'delivered', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_allocations', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'allocated', 'fulfilled', 'cancelled'])->default('pending')->change();
        });
    }
};
