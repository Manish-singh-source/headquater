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
        Schema::create('ewaybills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('einvoice_id')->constrained('e_invoices')->cascadeOnDelete();
            $table->string('ewb_no')->nullable();
            $table->datetime('ewb_dt')->nullable();
            $table->datetime('ewb_valid_till')->nullable();
            $table->string('ewaybill_pdf')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['invoice_id', 'ewb_no', 'ewb_dt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ewaybills');
    }
};
