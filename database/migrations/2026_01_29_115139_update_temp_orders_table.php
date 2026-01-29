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
            $table->string('portal_code')->nullable()->after('hsn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_orders', function (Blueprint $table) {
            //
            $table->dropColumn('portal_code');
        });
    }
};
