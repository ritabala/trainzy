<?php

namespace App\Livewire\Settings\Billing;

use Livewire\Component;
use Stripe\StripeClient;
use App\Models\Subscription;
use App\Models\GlobalPaymentGateway;

class PlanDetails extends Component
{
    public $subscription;
    public $package;
    public $modules;
    public $currency;
    public $stripeSubscription;

    public function mount()
    {
        $this->loadSubscription();
    }

    public function loadSubscription()
    {
        $gym = gym();
        $this->subscription = $gym->activePackageSubscription();

        if ($this->subscription) {
            $this->stripeSubscription = Subscription::where('gym_id', $gym->id)->where('stripe_id', $this->subscription->stripe_session_id)->first();
        }
        
        $this->package = $this->subscription->package;
        $this->modules = $this->package->modules()
            ->where('status', 'active')
            ->get();
        $this->currency = $this->package->currency;
    
    }

    public function cancelSubscription()
    {
        $stripeGateway = GlobalPaymentGateway::first();
        
        if ($stripeGateway && $stripeGateway->stripe_status) {
            $stripe = new StripeClient($stripeGateway->stripe_secret);
        }

        if (!$this->stripeSubscription) {
            return redirect()->route('settings.billing.index')->with('error', __('settings.billing.subscription_not_found'));
        }

        try {
            $subscription = $stripe->subscriptions->retrieve($this->stripeSubscription->stripe_subscription_id);
        } catch (\Exception $e) {
            logger()->error('Stripe checkout session retrieval failed: ' . $e->getMessage());
            return redirect()->route('settings.billing.index')->with('error', __('settings.billing.stripe_session_retrieval_failed'));
        }

        $stripe->subscriptions->update($this->stripeSubscription->stripe_subscription_id, [
            'cancel_at_period_end' => true,
        ]);


        $this->stripeSubscription->stripe_status = 'canceled';
        $this->stripeSubscription->save();

        return redirect()->route('settings.billing.index')->with('success', __('settings.billing.subscription_cancelled'));
    }

    public function render()
    {
        return view('livewire.settings.billing.plan-details', [
            'subscription' => $this->subscription,
            'package' => $this->package,
            'modules' => $this->modules,
            'currency' => $this->currency,
        ]);
    }
} 