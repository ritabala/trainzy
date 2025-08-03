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
        Schema::create('offline_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_payment_gateway_id')->constrained('global_payment_gateways')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade');
            $table->string('billing_cycle')->nullable();
            $table->string('document_path');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_payment_details');
    }
}; 