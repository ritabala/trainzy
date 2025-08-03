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
        Schema::create('membership_frequencies', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade'); // Links to Memberships table
            $table->foreignId('frequency_id')->constrained('frequencies')->onDelete('cascade'); // Links to Frequencies table
            $table->decimal('price', 16, 2); // Membership price for this frequency
            $table->timestamps(); // Created_at & Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_frequencies');
    }
};
