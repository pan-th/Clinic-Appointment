<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();                      
            

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('doctor_name');        
            $table->date('appointment_date');     
            $table->time('appointment_time');     
            $table->text('reason');               


            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};