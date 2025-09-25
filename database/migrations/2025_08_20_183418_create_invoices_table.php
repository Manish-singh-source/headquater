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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->integer('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('sales_order_id')->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->decimal('round_off', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('po_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
