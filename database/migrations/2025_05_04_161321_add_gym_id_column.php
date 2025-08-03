<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Gym;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'activity_classes',
            'available_time_slots',
            'body_measurement_values',
            'body_measurements',
            'body_metric_targets',
            'body_metric_types',
            'currencies',
            'documents',
            'invoices',
            'invoice_detail_taxes',
            'invoice_details',
            'member_enrollments',
            'membership_activity_classes',
            'membership_frequencies',
            'membership_services',
            'memberships',
            'payments',
            'product_taxes',
            'products',
            'progress_photos',
            'recurring_sessions',
            'services',
            'staff_details',
            'staff_types',
            'taxes',
            'user_memberships',
            'users',
            'messages'
        ];

        $gym = Gym::first();

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('gym_id')->after('id')->nullable()->constrained('gyms')->onDelete('cascade');
            });

            if ($gym) {
                DB::table($table)->update(['gym_id' => $gym->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'activity_classes',
            'available_time_slots',
            'body_measurement_values',
            'body_measurements',
            'body_metric_targets',
            'body_metric_types',
            'currencies',
            'documents',
            'frequencies',
            'invoices',
            'invoice_detail_taxes',
            'invoice_details',
            'member_enrollments',
            'membership_activity_classes',
            'membership_frequencies',
            'membership_services',
            'memberships',
            'payments',
            'product_taxes',
            'products',
            'progress_photos',
            'recurring_sessions',
            'services',
            'staff_details',
            'staff_types',
            'taxes',
            'user_memberships',
            'users',
            'messages'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['gym_id']);
                $table->dropColumn('gym_id');
            });
        }
    }
};
