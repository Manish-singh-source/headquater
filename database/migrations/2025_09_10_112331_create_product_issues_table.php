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
        Schema::create('product_issues', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_id')->nullable();
            $table->string('vendor_pi_id')->nullable();
            $table->string('vendor_pi_product_id')->nullable();
            $table->string('vendor_sku_code')->nullable();
            $table->string('quantity_requirement')->nullable();
            $table->string('available_quantity')->nullable();
            $table->string('quantity_received')->nullable()->default(0);
            $table->string('issue_item')->default(0);
            $table->string('issue_reason')->nullable();
            $table->string('issue_description')->nullable();
            $table->enum('issue_from', ['warehouse', 'vendor'])->nullable()->comment('warehouse - Issue generated from warehouse for customer order, vendor - Issue generated from warehouse for vendors received purchase order');
            $table->enum('issue_status', ['pending', 'return', 'accept', 'completed'])->nullable()->default('pending')->comment('pending, return, accept,completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_issues');
    }
};
