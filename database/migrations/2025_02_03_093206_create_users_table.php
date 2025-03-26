<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    if (!Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id')->unique(); // Employee ID with unique constraint
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['Admin', 'Employee'])->default('Employee');
            $table->boolean('is_active')->default(true); // Default to active
            $table->timestamps();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
