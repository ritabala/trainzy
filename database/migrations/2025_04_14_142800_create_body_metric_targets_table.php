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
        Schema::create('body_metric_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('body_metric_type_id')->constrained()->onDelete('cascade');
            $table->decimal('target_value', 8, 2)->nullable();
            $table->timestamps();

            // Each user can only have one target per metric type
            $table->unique(['user_id', 'body_metric_type_id'], 'unique_user_metric_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_metric_targets');
    }
}; 