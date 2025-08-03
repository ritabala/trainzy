<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->nullable()->constrained('gyms');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('available_time_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('activity_class_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('role_type', ['member', 'staff', 'admin']);
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();

            $table->enum('status', ['present', 'absent', 'late'])->nullable();
            $table->enum('method', ['manual', 'scanner'])->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
