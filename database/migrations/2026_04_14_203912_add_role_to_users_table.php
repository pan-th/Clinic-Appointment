<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This runs when you do php artisan migrate
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adds a "role" column after the "email" column
            // A user can only be either 'admin' or 'patient'
            // New users are automatically 'patient' by default
            $table->enum('role', ['admin', 'patient'])->default('patient')->after('email');
        });
    }

    // This runs when you do php artisan migrate:rollback
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};