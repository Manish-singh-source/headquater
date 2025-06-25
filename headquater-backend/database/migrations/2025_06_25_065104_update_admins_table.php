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
        Schema::table('admins', function (Blueprint $table) {
            // Unique keys
            $table->unique('user_name', 'admins_user_name_unique');
            $table->unique('email', 'admins_email_unique');

            // Index key
            $table->index('uid', 'uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->dropUnique('admins_user_name_unique');
            $table->dropUnique('admins_email_unique');
            $table->dropIndex('uid');
        });
    }
};
