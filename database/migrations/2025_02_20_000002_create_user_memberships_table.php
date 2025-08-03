<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_id')->constrained()->onDelete('cascade');
            // Membership details
            $table->foreignId('membership_frequency_id')->nullable()->constrained('membership_frequencies')->onDelete('cascade');
            $table->date('membership_start_date')->nullable();
            $table->date('membership_expiry_date')->nullable(); // Auto-calculated
            $table->enum('membership_status', ['active', 'expired', 'cancelled', 'suspended', 'upcoming'])->default('active');
            $table->boolean('auto_renewal')->default(false);
            $table->date('last_renewal_date')->nullable();
            $table->date('next_renewal_date')->nullable();
            $table->foreignId('parent_membership_id')->nullable()->constrained('user_memberships')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_memberships');
    }
};
