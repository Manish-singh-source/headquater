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
        Schema::table('ewaybills', function (Blueprint $table) {
            //
            $table->string('ewaybill_status')->nullable();
            $table->string('ewaybill_cancel_reason')->nullable();
            $table->string('ewaybill_cancel_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ewaybills', function (Blueprint $table) {
            //
            $table->dropColumn('ewaybill_status');
            $table->dropColumn('ewaybill_cancel_reason');
            $table->dropColumn('ewaybill_cancel_remarks');
        });
    }
};
