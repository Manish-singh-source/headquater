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
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('uid', 100)->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->string('name', 70)->nullable();
            $table->string('user_name', 70)->nullable();
            $table->string('email', 70)->nullable();
            $table->string('phone', 70)->nullable();
            $table->string('image', 120)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('password')->nullable();

            $table->enum('status', ['0', '1'])->default('1')->comment('Active : 1, Inactive : 0');

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
