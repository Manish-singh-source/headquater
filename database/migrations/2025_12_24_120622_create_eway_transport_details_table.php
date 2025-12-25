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
        Schema::create('eway_transport_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ewaybill_id')->constrained('ewaybills')->cascadeOnDelete();
            $table->string('transportation_mode')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('transporter_name')->nullable();
            $table->string('transporter_document_number')->nullable();
            $table->string('transporter_document_date')->nullable();
            $table->string('place_of_consignor')->nullable();
            $table->string('state_of_consignor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eway_transport_details');
    }
};
