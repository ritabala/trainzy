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
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_type_id')->nullable()->constrained('staff_types');
            $table->date('date_of_joining');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->nullable();
            $table->json('certifications')->nullable(); // Store multiple certifications as JSON
            $table->string('specialization')->nullable();
            $table->text('medical_history')->nullable();
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate staff entries
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_details');
    }
}; 