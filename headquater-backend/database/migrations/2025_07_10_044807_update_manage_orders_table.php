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
        Schema::table('manage_orders', function (Blueprint $table) {
            //
            $table->enum('status', ['0', '1', '2'])->comment('0: pending, 1: completed, 2: on hold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manage_orders', function (Blueprint $table) {
            //
            $table->dropColumn('status');
        });
    }
};
