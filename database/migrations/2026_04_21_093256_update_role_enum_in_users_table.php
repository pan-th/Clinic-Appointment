<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL does not allow direct ENUM modification via Blueprint.
        // We use a raw ALTER TABLE statement to expand the allowed values.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','patient','nurse','doctor') NOT NULL DEFAULT 'patient'");
    }

    public function down(): void
    {
        // Revert back to original two-value ENUM if you roll back
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','patient') NOT NULL DEFAULT 'patient'");
    }
};