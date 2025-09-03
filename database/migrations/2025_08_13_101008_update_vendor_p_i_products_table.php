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
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            //
            $table->string('quantity_received')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            //
            $table->dropColumn('quantity_received');
        });
    }
};
