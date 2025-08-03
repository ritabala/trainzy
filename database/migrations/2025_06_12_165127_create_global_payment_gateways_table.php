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
        Schema::create('global_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('live_stripe_key')->nullable();
            $table->string('live_stripe_secret')->nullable();
            $table->string('test_stripe_key')->nullable();
            $table->string('test_stripe_secret')->nullable();
            $table->boolean('stripe_status')->default(false);
            $table->enum('stripe_environment', ['test', 'live'])->default('test');
            $table->string('offline_method_name')->nullable();
            $table->text('offline_method_description')->nullable();
            $table->boolean('offline_method_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_payment_gateways');
    }
};
