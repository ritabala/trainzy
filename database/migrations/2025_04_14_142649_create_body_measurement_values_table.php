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
        Schema::create('body_measurement_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('body_measurement_id')->constrained()->onDelete('cascade');
            $table->foreignId('body_metric_type_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Each metric type should only appear once per measurement
            // Use a shorter name for the unique index
            $table->unique(['body_measurement_id', 'body_metric_type_id'], 'unique_measurement_metric');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_measurement_values');
    }
};
