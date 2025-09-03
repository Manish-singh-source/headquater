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
            $table->string('issue_description')->nullable();
            $table->enum('issue_status', ['pending', 'return', 'accept', 'completed'])->nullable()->default('pending')->comment('pending, return, accept,completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            //
            $table->dropColumn('issue_description');
            $table->dropColumn('issue_status');
        });
    }
};
