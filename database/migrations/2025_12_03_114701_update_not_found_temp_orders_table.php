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
        Schema::table('not_found_temp_orders', function (Blueprint $table) {
            //
            $table->text('facility_location')->nullable()->after('facility_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('not_found_temp_orders', function (Blueprint $table) {
            //
            $table->string('facility_location')->nullable()->after('facility_name')->change();
        });
    }
};
