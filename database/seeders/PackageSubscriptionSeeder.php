<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\Package;
use App\Models\GymPackageSubscription;
use App\Models\PackagePayment;
use Illuminate\Support\Facades\DB;

class PackageSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Find the gym created by GymSeeder
            $gyms = Gym::all();

            foreach ($gyms as $gym) {

                // Find the lifetime package created by PackageSeeder
                $lifetimePackage = Package::where('package_type', 'lifetime')->first();
                
                // Create the subscription record
                $gymPackageSubscription = GymPackageSubscription::create([
                    'gym_id' => $gym->id,
                    'package_id' => $lifetimePackage->id,
                    'is_active' => true,
                    'starts_on' => now(),
                    'ends_on' => null, // null for lifetime package
                    'amount' => (float)$lifetimePackage->lifetime_price,
                    'billing_cycle' => 'lifetime',
                    'status' => 'completed',
                    'is_active' => true
                ]);

                PackagePayment::create([
                    'gym_id' => $gym->id,
                    'package_id' => $lifetimePackage->id,
                    'subscription_id' => $gymPackageSubscription->id,
                    'amount' => (float)$gymPackageSubscription->amount,
                    'payment_gateway' => 'offline',
                    'payment_status' => 'completed',
                    'transaction_id' => 'OFFLINE-' . uniqid(),
                    'paid_on' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating package subscription: ' . $e->getMessage());
            throw $e;
        }
    }
} 