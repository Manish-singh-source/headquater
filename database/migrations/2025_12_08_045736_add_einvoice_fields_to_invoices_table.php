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
            $table->string('irn')->nullable();
            $table->string('ack_no')->nullable();
            $table->datetime('ack_dt')->nullable();
            $table->text('signed_invoice')->nullable();
            $table->text('signed_qr_code')->nullable();
            $table->string('ewb_no')->nullable();
            $table->datetime('ewb_dt')->nullable();
            $table->datetime('ewb_valid_till')->nullable();
            $table->string('einvoice_pdf')->nullable();
            $table->string('ewaybill_pdf')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->string('einvoice_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'irn',
                'ack_no',
                'ack_dt',
                'signed_invoice',
                'signed_qr_code',
                'ewb_no',
                'ewb_dt',
                'ewb_valid_till',
                'einvoice_pdf',
                'ewaybill_pdf',
                'qr_code_url',
                'einvoice_status',
            ]);
        });
    }
};
