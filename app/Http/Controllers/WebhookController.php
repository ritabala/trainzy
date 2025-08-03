<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\GymPackageSubscription;
use App\Models\PackagePayment;
use Stripe\Event;

class WebhookController extends CashierController
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            // Add more event handlers as needed
        }

        return response('Webhook Handled', 200);
    }

    /**
     * Handle invoice payment succeeded event.
     *
     * @param  \Stripe\Invoice  $invoice
     * @return void
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        $subscription = GymPackageSubscription::where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if ($subscription) {
            // Update subscription status
            $subscription->update([
                'is_active' => true,
                'status' => 'active',
            ]);

            // Create payment record
            PackagePayment::create([
                'gym_id' => $subscription->gym_id,
                'package_id' => $subscription->package_id,
                'subscription_id' => $subscription->id,
                'amount' => $invoice->amount_paid / 100, // Convert from cents
                'payment_gateway' => PackagePayment::GATEWAY_STRIPE,
                'payment_status' => PackagePayment::STATUS_COMPLETED,
                'transaction_id' => $invoice->payment_intent,
                'paid_on' => now(),
            ]);
        }
    }

    /**
     * Handle subscription deleted event.
     *
     * @param  \Stripe\Subscription  $subscription
     * @return void
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        $gymSubscription = GymPackageSubscription::where('stripe_subscription_id', $subscription->id)
            ->first();

        if ($gymSubscription) {
            $gymSubscription->update([
                'is_active' => false,
                'status' => 'cancelled',
                'ends_on' => now(),
            ]);
        }
    }
} 