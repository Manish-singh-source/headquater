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
            $table->string('portal_code')->nullable()->after('vendor_sku_code');
            $table->string('item_code')->nullable()->after('portal_code');
            $table->string('vendor_invoice_no')->nullable()->after('item_code');

            $table->unique(
                ['portal_code', 'item_code', 'vendor_invoice_no', 'vendor_sku_code'],
                'vendor_pi_products_unique_vendor_invoice_item'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            $table->dropUnique('vendor_pi_products_unique_vendor_invoice_item');
            $table->dropColumn(['portal_code', 'item_code', 'vendor_invoice_no']);
        });
    }
};
