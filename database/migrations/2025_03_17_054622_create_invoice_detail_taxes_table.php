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
        Schema::create('invoice_detail_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_detail_id')->constrained('invoice_details')->onDelete('cascade');
            $table->foreignId('tax_id')->constrained('taxes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_detail_taxes', function (Blueprint $table) {
            $table->dropForeign(['invoice_detail_id']);
            $table->dropForeign(['tax_id']);
        });
        Schema::dropIfExists('invoice_detail_taxes');
    }
};
