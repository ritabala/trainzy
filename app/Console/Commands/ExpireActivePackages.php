<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package;
use App\Models\GymPackageSubscription;

class ExpireActivePackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-active-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $defaultPackage = Package::where('package_type', 'default')->first();

        // Expire standard packages
        $subscriptions = GymPackageSubscription::where('is_active', 1)
            // ->where('ends_on', '<', now())
            ->whereHas('package', function ($query) {
                $query->where('package_type', 'standard');
            })
            ->select('id', 'gym_id')
            ->get()->toArray();

        GymPackageSubscription::whereIn('id', array_column($subscriptions, 'id'))->update(['is_active' => false]);

        foreach ($subscriptions as $subscription) {
            GymPackageSubscription::create([
                'gym_id' => $subscription['gym_id'],
                'package_id' => $defaultPackage->id,
                'starts_on' => now(),
                'is_active' => true,
                'stripe_session_id' => null,
                'status' => 'completed',
                'billing_cycle' => 'free'
            ]);                
        }

        $this->info('Expired ' . count($subscriptions) . ' subscriptions');

        //Expire Trial Packages
        $trial_subscriptions = GymPackageSubscription::where('is_active', 1)
            ->where('ends_on', '<', now())
            ->whereHas('package', function ($query) {
                $query->where('package_type', 'trial');
            })
            ->select('id', 'gym_id')
            ->get()->toArray();

        GymPackageSubscription::whereIn('id', array_column($trial_subscriptions, 'id'))->update(['is_active' => false]);


        foreach ($trial_subscriptions as $subscription) {
            GymPackageSubscription::create([
                'gym_id' => $subscription['gym_id'],
                'package_id' => $defaultPackage->id,
                'starts_on' => now(),
                'is_active' => true,
                'stripe_session_id' => null,
                'status' => 'completed',
                'billing_cycle' => 'free'
            ]);
        }

        $this->info('Expired ' . count($trial_subscriptions) . ' trial subscriptions');

    }
}
