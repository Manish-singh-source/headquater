<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Customer Sales SKU filters invoices by created_at, then joins by id.
            $table->index(['created_at', 'id'], 'invoices_created_at_id_idx');
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            // Covers the filtered invoice-to-sales-order-product lookup used by the report.
            $table->index(['invoice_id', 'sales_order_product_id', 'id'], 'invoice_details_report_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropIndex('invoice_details_report_lookup_idx');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_created_at_id_idx');
        });
    }
};
