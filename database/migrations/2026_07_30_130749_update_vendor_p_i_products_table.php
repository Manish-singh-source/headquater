<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendor_p_i_products', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor_p_i_products', 'portal_code')) {
                $table->string('portal_code', 150)->nullable()->after('vendor_sku_code');
            }

            if (! Schema::hasColumn('vendor_p_i_products', 'item_code')) {
                $table->string('item_code', 150)->nullable()->after('portal_code');
            }

            if (! Schema::hasColumn('vendor_p_i_products', 'vendor_invoice_no')) {
                $table->string('vendor_invoice_no', 150)->nullable()->after('item_code');
            }
        });

        DB::statement('ALTER TABLE `vendor_p_i_products` MODIFY `portal_code` VARCHAR(150) NULL');
        DB::statement('ALTER TABLE `vendor_p_i_products` MODIFY `item_code` VARCHAR(150) NULL');
        DB::statement('ALTER TABLE `vendor_p_i_products` MODIFY `vendor_invoice_no` VARCHAR(150) NULL');

        Schema::table('vendor_p_i_products', function (Blueprint $table) {
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