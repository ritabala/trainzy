<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->enum('recipient_type', ['members', 'staff']);
            $table->json('recipient_ids')->nullable(); // used only for selected_* types
            $table->foreignId('activity_class_id')->nullable()->constrained('activity_classes')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
}; 