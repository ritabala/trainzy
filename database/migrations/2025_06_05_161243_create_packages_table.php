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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->enum('plan_type', ['free', 'paid'])->default('paid');
            $table->string('package_name');
            $table->enum('package_type', ['standard', 'lifetime', 'trial', 'default'])->default('standard');
            $table->foreignId('currency_id')->nullable()->constrained('global_currencies')->onDelete('set null');
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('annual_price', 10, 2)->nullable();
            $table->decimal('lifetime_price', 10, 2)->nullable();
            $table->integer('trial_days')->nullable();
            $table->string('trial_message')->nullable();
            $table->integer('notification_before_days')->nullable();            
            $table->integer('max_members')->default(0);
            $table->integer('max_staff')->default(0);
            $table->integer('max_classes')->default(0);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
