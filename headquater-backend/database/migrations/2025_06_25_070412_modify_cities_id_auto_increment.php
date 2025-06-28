<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE `cities` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE `cities` AUTO_INCREMENT = 1;");
        });
    }
};
