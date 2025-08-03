<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
use App\Models\GymPackageSubscription;
use App\Models\Subscription;
use App\Models\GlobalPaymentGateway;
use App\Models\PackagePayment;
use Stripe\StripeClient;

class BillingController extends Controller
{
    /**
     * Display the billing settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_billing'), 403);
        return view('settings.billing.index');
    }

    /**
     * Display the current subscription details.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_billing'), 403);
        $gym = gym();
        $currentSubscription = $gym->activePackageSubscription();
        
        return view('settings.billing.show', [
            'subscription' => $currentSubscription
        ]);
    }

    /**
     * Display the purchase history.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_billing'), 403);
        $gym = gym();
        $purchaseHistory = GymPackageSubscription::where('gym_id', $gym->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('settings.billing.history', [
            'purchaseHistory' => $purchaseHistory
        ]);
    }

    /**
     * Display the offline requests.
     *
     * @return \Illuminate\View\View
     */
    public function offlineRequests()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_billing'), 403);
        // TODO: Implement offline requests functionality
        return view('settings.billing.offline-requests', [
            'offlineRequests' => collect([])
        ]);
    }

    public function upgrade()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('upgrade_plan'), 403);
        return view('settings.billing.upgrade');
    }

    public function stripeSuccess()
    {
        if(request()->session_id) {
            $gym = gym();
            $gymPayment = GymPackageSubscription::where('stripe_session_id', request()->session_id)->firstOrFail();

            $stripeGateway = GlobalPaymentGateway::first();
            $stripe = new StripeClient($stripeGateway->stripe_secret);

            try {
                $session = $stripe->checkout->sessions->retrieve(request()->session_id);
            } catch (\Exception $e) {
                logger()->error('Stripe checkout session retrieval failed: ' . $e->getMessage());
                return redirect()->route('settings.billing.index')->with('error', __('settings.billing.stripe_session_retrieval_failed'));
            }

            $planId = null;
            $subscriptionId = null;
            if ($session->invoice) {
                $invoice = $stripe->invoices->retrieve($session->invoice);
                $lineItem = $invoice->lines->data[0];
                $planId = $lineItem->plan->id;
                $subscriptionId = $lineItem->subscription;
            }

            if ($gymPayment) {

                GymPackageSubscription::where('gym_id', $gym->id)->update(['is_active' => false]);

                $gymPayment->status = 'completed';
                $gymPayment->is_active = true;
                $gymPayment->save();
            }

            $subscription = Subscription::create([
                'gym_id' => $gym->id,
                'type' => $gymPayment->billing_cycle == 'lifetime' ? 'one_time' : 'recurring',
                'stripe_id' => $gymPayment->stripe_session_id,
                'stripe_subscription_id' => $subscriptionId,
                'stripe_status' => 'active',
                'stripe_price' => $gymPayment->stripe_price_id,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => ($gymPayment->billing_cycle == 'monthly') ? now()->addDays(30) : ($gymPayment->billing_cycle == 'annual' ? now()->addDays(365) : null),
            ]);

            PackagePayment::create([
                'gym_id' => $gym->id,
                'package_id' => $gymPayment->package_id,
                'subscription_id' => $subscription->id,
                'amount' => $gymPayment->amount,
                'payment_gateway' => PackagePayment::GATEWAY_STRIPE,
                'payment_status' => PackagePayment::STATUS_COMPLETED,
                'transaction_id' => $gymPayment->stripe_session_id,
                'paid_on' => now(),
            ]);

            return redirect()->route('settings.billing.index')->with('success', __('settings.billing.stripe_subscription_updated'));
        }
     
        // return view('settings.billing.stripe-success');
    }
} 