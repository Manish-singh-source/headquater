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
        Schema::create('packaging_upload_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();
            $table->string('mode')->default('warehouse_submit')->index(); // warehouse_submit | admin_correction
            $table->string('file_name')->nullable();
            $table->string('file_hash', 128)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_upload_batches');
    }
};

