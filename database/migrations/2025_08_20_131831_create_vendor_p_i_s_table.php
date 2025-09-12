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
        Schema::create('vendor_p_i_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('vendor_code')->nullable();
            $table->integer('sales_order_id')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('total_paid_amount')->nullable();
            $table->string('total_due_amount')->nullable();
            $table->enum('status', ['pending', 'approve', 'reject', 'completed'])->default('pending');
            $table->string('approve_or_reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_p_i_s');
    }
};
