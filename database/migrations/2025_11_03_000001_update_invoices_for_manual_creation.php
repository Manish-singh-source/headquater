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
        Schema::table('invoices', function (Blueprint $table) {
            // Make sales_order_id nullable for manual invoices
            $table->foreignId('sales_order_id')->nullable()->change();
            
            // Add payment-related fields
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('balance_due', 10, 2)->default(0)->after('paid_amount');
            $table->string('payment_mode')->nullable()->after('balance_due');
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid')->after('payment_mode');
            $table->enum('invoice_type', ['sales_order', 'manual'])->default('sales_order')->after('payment_status');
            $table->text('notes')->nullable()->after('invoice_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('sales_order_id')->nullable(false)->change();
            $table->dropColumn([
                'subtotal',
                'tax_amount',
                'discount_amount',
                'paid_amount',
                'balance_due',
                'payment_mode',
                'payment_status',
                'invoice_type',
                'notes'
            ]);
        });
    }
};

