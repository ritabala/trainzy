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
        Schema::create('available_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_class_id')->constrained('activity_classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('References staff user as instructor');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['weekly', 'date_specific'])->default('weekly');
            $table->integer('day_of_week')->nullable(); // Only used if type is weekly
            $table->date('date')->nullable(); // Only used if type is date_specific
            $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_time_slots');
    }
}; 