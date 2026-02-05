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
        Schema::table('temp_orders', function (Blueprint $table) {
            //
            $table->decimal('available_quantity_track', 15, 2)->nullable()->after('available_quantity')->default(0);
            $table->decimal('unavailable_quantity_track', 15, 2)->nullable()->after('unavailable_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_orders', function (Blueprint $table) {
            //
            $table->dropColumn('available_quantity_track');
            $table->dropColumn('unavailable_quantity_track');
        });
    }
};
