<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Stores the ID of the nurse who last confirmed or cancelled this appointment.
            // Nullable because new appointments have not been actioned yet.
            // SET NULL means if the nurse account is deleted, this field becomes null
            // instead of deleting the appointment record.
            $table->unsignedBigInteger('actioned_by_nurse_id')
                  ->nullable()
                  ->after('notes');

            $table->foreign('actioned_by_nurse_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Stores exactly when the nurse performed the status update.
            // Nullable because unactioned appointments have no timestamp yet.
            $table->timestamp('actioned_at')
                  ->nullable()
                  ->after('actioned_by_nurse_id');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Must drop foreign key before dropping the column
            $table->dropForeign(['actioned_by_nurse_id']);
            $table->dropColumn('actioned_by_nurse_id');
            $table->dropColumn('actioned_at');
        });
    }
};