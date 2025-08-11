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
            $table->string('issue_item')->default(0);
            $table->string('issue_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            //
            $table->dropColumn('issue_item');
            $table->dropColumn('issue_reason');
        });
    }
};
