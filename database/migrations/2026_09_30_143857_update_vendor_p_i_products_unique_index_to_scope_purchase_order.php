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
            $table->dropUnique('vendor_pi_products_unique_vendor_invoice_item');
            $table->index('purchase_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            $table->dropIndex(['purchase_order_id']);
            $table->unique(
                ['portal_code', 'item_code', 'vendor_invoice_no', 'vendor_sku_code'],
                'vendor_pi_products_unique_vendor_invoice_item'
            );
        });
    }
};
