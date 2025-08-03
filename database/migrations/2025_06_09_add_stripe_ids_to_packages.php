<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('stripe_product_id')->nullable()->after('notification_before_days');
            $table->string('stripe_monthly_price_id')->nullable()->after('stripe_product_id');
            $table->string('stripe_annual_price_id')->nullable()->after('stripe_monthly_price_id');
            $table->string('stripe_lifetime_price_id')->nullable()->after('stripe_annual_price_id');
        });

        Schema::table('gym_package_subscriptions', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('package_id');
            $table->string('stripe_price_id')->nullable()->after('stripe_session_id');
            $table->string('stripe_customer_id')->nullable()->after('stripe_price_id');
            $table->string('status')->nullable()->after('stripe_customer_id');  
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_product_id',
                'stripe_monthly_price_id',
                'stripe_annual_price_id',
            ]);
        });

        Schema::table('gym_package_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_session_id',
                'stripe_price_id',
                'stripe_customer_id',
                'status',
            ]);
        });
    }
}; 