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
        Schema::create('gym_package_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');

            $table->string('billing_cycle')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);

            $table->timestamp('starts_on');
            $table->timestamp('ends_on')->nullable();
            $table->timestamp('expires_on')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_package_subscriptions');
    }
};
