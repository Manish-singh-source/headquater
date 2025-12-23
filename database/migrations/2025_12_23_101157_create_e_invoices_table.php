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
        Schema::create('e_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('irn')->nullable();
            $table->string('ack_no')->nullable();
            $table->datetime('ack_dt')->nullable();
            $table->text('signed_invoice')->nullable();
            $table->text('signed_qr_code')->nullable();
            $table->string('einvoice_pdf')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->string('einvoice_status')->nullable();
            $table->string('cancel_remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['invoice_id', 'irn', 'einvoice_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_invoices');
    }
};
