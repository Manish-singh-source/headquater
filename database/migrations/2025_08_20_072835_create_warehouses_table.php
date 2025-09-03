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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 70);
            $table->string('type', 70);
            $table->string('contact_person_name', 70)->nullable();
            $table->string('phone', 70);
            $table->string('alt_phone', 70)->nullable();
            $table->string('email', 70);
            $table->string('gst_number', 255)->nullable();
            $table->string('pan_number', 255)->nullable();
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255)->nullable();
            $table->text('licence_doc')->nullable();
            $table->unsignedBigInteger('max_storage_capacity')->nullable(); // corrected field name (see note)
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->integer('pincode');
            $table->string('operations')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('Active : 1,Inactive : 0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
