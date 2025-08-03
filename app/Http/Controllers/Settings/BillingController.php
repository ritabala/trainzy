<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\GymPackageSubscription;
use App\Models\PackagePayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function success(Request $request, $packageId)
    {
        try {
            $gym = gym();
            $package = Package::findOrFail($packageId);
            
            // Get the latest subscription from Stripe
            $stripeSubscription = $gym->subscriptions()->latest()->first();
            
            if (!$stripeSubscription) {
                throw new \Exception('No subscription found');
            }

            // Verify the subscription is active
            if ($stripeSubscription->stripe_status !== 'active') {
                throw new \Exception('Subscription is not active');
            }

            // Deactivate current subscription if exists
            $currentSubscription = $gym->activePackageSubscription();
            if ($currentSubscription) {
                $currentSubscription->update(['is_active' => false]);
            }

            // Create new subscription record
            $subscription = GymPackageSubscription::create([
                'gym_id' => $gym->id,
                'package_id' => $packageId,
                'stripe_subscription_id' => $stripeSubscription->stripe_id,
                'stripe_price_id' => $stripeSubscription->stripe_price,
                'stripe_customer_id' => $gym->stripe_id,
                'billing_cycle' => $request->billing_cycle ?? 'monthly',
                'starts_on' => now(),
                'ends_on' => $stripeSubscription->ends_at ?? now()->addYear(),
                'is_active' => true,
                'status' => 'active',
            ]);

            // Create payment record
            $price = $request->billing_cycle === 'monthly' ? $package->monthly_price : $package->annual_price;
            
            PackagePayment::create([
                'gym_id' => $gym->id,
                'package_id' => $packageId,
                'subscription_id' => $subscription->id,
                'amount' => $price,
                'payment_gateway' => PackagePayment::GATEWAY_STRIPE,
                'payment_status' => PackagePayment::STATUS_COMPLETED,
                'transaction_id' => $stripeSubscription->stripe_id,
                'paid_on' => now(),
            ]);

            session()->flash('success', __('package.subscription_activated'));
            return redirect()->route('settings.billing.index');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('settings.billing.index');
        }
    }
} 