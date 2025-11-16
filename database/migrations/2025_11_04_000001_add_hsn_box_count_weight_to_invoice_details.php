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
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->string('hsn')->nullable()->after('product_id');
            $table->integer('box_count')->nullable()->after('quantity');
            $table->decimal('weight', 10, 2)->nullable()->after('box_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn(['hsn', 'box_count', 'weight']);
        });
    }
};
