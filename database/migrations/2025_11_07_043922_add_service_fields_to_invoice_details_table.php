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
            $table->string('service_title')->nullable()->after('po_number');
            $table->string('service_category')->nullable()->after('service_title');
            $table->text('service_description')->nullable()->after('service_category');
            $table->string('campaign_name')->nullable()->after('service_description');
            $table->string('unit_type')->nullable()->after('campaign_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn([
                'service_title',
                'service_category',
                'service_description',
                'campaign_name',
                'unit_type',
            ]);
        });
    }
};

