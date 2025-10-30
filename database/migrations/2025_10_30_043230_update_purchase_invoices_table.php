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
        Schema::table('purchase_invoices', function (Blueprint $table) {
            //
            $table->string('invoice_no')->nullable()->after('vendor_code');
            $table->string('invoice_amount')->nullable()->after('invoice_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            //
            $table->dropColumn('invoice_no');
            $table->dropColumn('invoice_amount');
        });
    }
};
