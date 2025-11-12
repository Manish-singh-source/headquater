<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change approval_status enum to include 'draft' and set default to 'draft'
        DB::statement("ALTER TABLE warehouse_allocations MODIFY COLUMN approval_status ENUM('draft', 'pending', 'approved', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum without 'draft'
        DB::statement("ALTER TABLE warehouse_allocations MODIFY COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
