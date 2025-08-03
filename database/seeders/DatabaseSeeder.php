<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([            
            //Global SAAS related seeders
            GlobalCurrencySeeder::class,
            GlobalPaymentGatewaySeeder::class,
            GlobalSettingSeeder::class,
            ModuleSeeder::class,
            PackageSeeder::class,
            FrequencySeeder::class,
        ]);

        if (app()->environment('local') || app()->environment('demo')) {
            $this->call([
                //Gym related seeders
                GymSeeder::class,
                // CurrencySeeder::class,
                ServiceSeeder::class,
                ActivityClassSeeder::class,
                RolePermissionSeeder::class,
                TaxSeeder::class,
                StaffTypeSeeder::class,
                StaffSeeder::class,
                AvailableTimeSlotSeeder::class,
                MembershipSeeder::class,
                MemberSeeder::class,
                ProductSeeder::class,
                BodyMetricSeeder::class,
                InvoiceSeeder::class,
                PaymentSeeder::class,
                MessagesTableSeeder::class,
                AttendanceSeeder::class,
                PackageSubscriptionSeeder::class,
            ]);
        }
    }
}
