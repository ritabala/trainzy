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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Links invoice to a user
            $table->date('invoice_date'); // Invoice creation date
            $table->date('due_date'); // Invoice due date
            $table->string('invoice_prefix')->notNull();
            $table->string('invoice_number'); // Unique invoice number
            $table->enum('status', ['unpaid', 'paid', 'partially_paid', 'cancelled', 'overdue'])->default('unpaid'); // Invoice status
            $table->decimal('sub_total', 16, 2); // Total amount before tax
            $table->enum('discount_type', ['%', 'fixed'])->default('%'); // The type of discount (percentage or fixed amount)
            $table->decimal('discount_value', 16, 2); // The entered discount percentage or fixed amount.
            $table->decimal('discount_amount', 16, 2); // The calculated discount amount based on the discount type and value.
            $table->decimal('total_amount', 16, 2); // The final amount after applying the discount & taxes.
            $table->string('notes')->nullable();
            $table->foreignId('user_membership_id')->nullable()->constrained('user_memberships')->onDelete('cascade'); // Links invoice to a user membership
            $table->timestamps(); // Created_at & Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop foreign key before dropping table
            $table->dropForeign(['user_membership_id']); // Drop foreign key before dropping table
        });
        Schema::dropIfExists('invoices');
    }
};
