<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Module;
use Illuminate\Support\Facades\DB;
use App\Models\GlobalCurrency;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Create a default package
            $defaultPackage = Package::create([
                'plan_type' => 'free',
                'package_name' => 'Default Package',
                'max_members' => 5,
                'max_staff' => 1,
                'max_classes' => 2,
                'is_active' => true,
                'package_type' => 'default',
                'description' => 'This is a default package and cannot be deleted.',
            ]);

            // Create a trial package
            $trialPackage = Package::create([
                'plan_type' => 'free',
                'package_name' => 'Trial Package',
                'package_type' => 'trial',
                'is_active' => true,
                'max_members' => 10,
                'max_staff' => 2,
                'max_classes' => 5,
                'trial_days' => 30,
                'trial_message' => '30 Days Free Trial',
                'notification_before_days' => 5,
                'description' => 'This is a trial package.',
            ]);

            // Create a standard package
            $standardPackage = Package::create([
                'plan_type' => 'paid',
                'package_name' => 'Subscription Package',
                'package_type' => 'standard',
                'currency_id' => GlobalCurrency::first()->id,
                'monthly_price' => 99.99,
                'annual_price' => 999.99,
                'max_members' => 100,
                'max_staff' => 10,
                'max_classes' => 20,
                'is_active' => true,
                'description' => 'This is a subscription package',
                'stripe_monthly_price_id' => 'price_1RYJVdIzXrJyGIXYq4vbF7VE',
                'stripe_annual_price_id' => 'price_1RYsopIzXrJyGIXY6m9pcQjf',
            ]);

            // Create a lifetime package
            $lifetimePackage = Package::create([
                'plan_type' => 'paid',
                'package_name' => 'Lifetime Package',
                'package_type' => 'lifetime',
                'currency_id' => GlobalCurrency::first()->id,
                'lifetime_price' => 1999.99,
                'max_members' => 100,
                'max_staff' => 10,
                'max_classes' => 20,
                'is_active' => true,
                'description' => 'This is a lifetime package.',
                'stripe_lifetime_price_id' => 'price_1RYstWIzXrJyGIXYNI3rToBz',
            ]);

            // Get some modules and attach them to the package
            $primaryModules = Module::where('is_additional', false)->where('status', 'active')->get();
            $defaultPackage->modules()->attach($primaryModules->pluck('id'));
            $standardPackage->modules()->attach($primaryModules->pluck('id'));
            
            $allModules = Module::where('status', 'active')->get();
            $lifetimePackage->modules()->attach($allModules->pluck('id'));
            $trialPackage->modules()->attach($allModules->pluck('id'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating test package: ' . $e->getMessage());
            throw $e;
        }
    }
} 