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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('user_membership_id')->nullable()->constrained('user_memberships')->onDelete('cascade'); // Links invoice to a user membership
            $table->foreignId('membership_frequency_id')->nullable()->constrained('membership_frequencies')->onDelete('cascade'); // Links to Membership Frequency
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade'); // If product is purchased
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 16, 2);
            $table->decimal('amount', 16, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['user_membership_id']);
            $table->dropForeign(['membership_frequency_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('invoice_details');
    }
};
