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
        // Add 'rejected' to the enum values of 'status' column
        DB::statement("
            ALTER TABLE purchase_orders 
            MODIFY COLUMN status ENUM('pending', 'completed', 'received', 'ready_to_ship', 'ready_to_package', 'rejected') DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum values to original without 'rejected'
        DB::statement("
            ALTER TABLE purchase_orders 
            MODIFY COLUMN status ENUM('pending', 'completed', 'received', 'ready_to_ship', 'ready_to_package') DEFAULT 'pending'
        ");
    }
};
