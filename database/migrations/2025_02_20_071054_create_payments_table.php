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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Links payment to a user
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade'); // Links payment to a invoice
            $table->decimal('amount_paid', 16, 2);
            $table->string('transaction_no')->nullable()->unique();
            $table->dateTime('payment_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'partially_paid', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_mode', ['cash', 'bank_transfer', 'cheque', 'credit_card', 'debit_card', 'mobile_money', 'other'])->default('cash');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['invoice_id']);
        });
        Schema::dropIfExists('payments');
    }
};
