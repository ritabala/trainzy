<?php

namespace App\Livewire\Settings\Billing;

use Livewire\Component;
use App\Models\Package;
use App\Models\GlobalCurrency;
use Livewire\Attributes\Computed;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Exception;
use App\Models\GymPackageSubscription;
use App\Models\PackagePayment;
use Stripe\StripeClient;
use App\Models\GlobalPaymentGateway;
use Livewire\WithPagination;
use App\Models\OfflinePaymentDetail;
use Livewire\WithFileUploads;

class UpgradePlan extends Component
{
    use WithPagination, WithFileUploads;

    public $packages;
    public $currentPackageId;
    public $selectedCurrencyId;
    public $selectedBillingCycle = 'monthly';
    public $currencies;
    public $checkoutSessionId;
    public $currentPackageFrequency;
    public $showPaymentModal = false;
    public $selectedPackageId;
    public $selectedPaymentGateway;
    public $offlinePaymentMethods;
    public $selectedOfflineMethod;
    public $showOfflineReceiptModal = false;
    public $offlineReceipt;
    public $offlineDescription;
    protected $stripe;
    public $isStripeEnabled;
    public $stripeGateway;

    protected $rules = [
        'offlineReceipt' => 'required|file|max:5120', // 5MB max
        'offlineDescription' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->stripeGateway = GlobalPaymentGateway::first();
        $this->isStripeEnabled = $this->stripeGateway && $this->stripeGateway->stripe_status;

        if ($this->isStripeEnabled) {
            $this->stripe = new StripeClient($this->stripeGateway->stripe_secret);
        }
        
        $gym = gym();
        $subscription = $gym->activePackageSubscription();
        $this->currentPackageId = $subscription ? $subscription->package_id : null;
        $this->currentPackageFrequency = $subscription ? $subscription->billing_cycle : null;
        $this->currencies = GlobalCurrency::all();
        $this->selectedCurrencyId = null;
        $this->loadPackages();
        $this->loadPaymentGateways();


    }

    public function updatedSelectedCurrencyId()
    {
        $this->loadPackages();
    }

    public function updatedSelectedBillingCycle()
    {
        $this->loadPackages();
    }

    protected function loadPackages()
    {
        $query = Package::with(['modules', 'currency'])
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNotIn('package_type', ['trial', 'default']);
            });

        // Filter by selected currency if one is selected
        if ($this->selectedCurrencyId) {
            $query->where('currency_id', $this->selectedCurrencyId);
        }

        $this->packages = $query->get()
            ->map(function ($package) {
                // Convert prices based on selected currency if needed
                // This is where you would implement currency conversion logic
                return $package;
            });
    }

    #[Computed]
    public function selectedCurrency()
    {
        return $this->currencies->firstWhere('id', $this->selectedCurrencyId);
    }

    protected function loadPaymentGateways()
    {
        $this->offlinePaymentMethods = GlobalPaymentGateway::where('offline_method_status', true)
            ->whereNotNull('offline_method_name')
            ->get();
    }

    public function openPaymentModal($packageId)
    {
        $this->selectedPackageId = $packageId;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedPackageId = null;
        $this->selectedPaymentGateway = null;
        $this->selectedOfflineMethod = null;
    }

    public function selectPaymentGateway($gateway)
    {
        $this->selectedPaymentGateway = $gateway;
        
        if ($gateway === 'stripe') {
            try {
                $gym = gym();
                $package = Package::findOrFail($this->selectedPackageId);
                $stripe = new StripeClient($this->stripeGateway->stripe_secret);

                if ($package->package_type === 'lifetime') {
                    $this->selectedBillingCycle = 'lifetime';
                    $priceId = $package->stripe_lifetime_price_id;
                } else {
                    $priceId = $this->selectedBillingCycle === 'monthly' 
                        ? $package->stripe_monthly_price_id
                        : $package->stripe_annual_price_id;
                }
                
                if (!$priceId) {
                    throw new Exception(__('package.stripe_price_not_configured'));
                }

                // Create or get Stripe customer
                if (!$gym->stripe_id) {
                    $customer = $stripe->customers->create([
                        'email' => $gym->email,
                        'name' => $gym->name,
                        'metadata' => [
                            'gym_id' => $gym->id
                        ]
                    ]);

                    $gym->stripe_id = $customer->id;
                    $gym->save();
                }

                // Create checkout session
                $checkoutSession = $stripe->checkout->sessions->create([
                    'customer' => $gym->stripe_id,
                    'line_items' => [[
                        'price' => $priceId,
                        'quantity' => 1
                    ]],
                    'mode' => $this->selectedBillingCycle === 'lifetime' ? 'payment' : 'subscription',
                    'success_url' => route('settings.billing.stripe_success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('settings.billing.index'),
                    'client_reference_id' => $gym->id,
                ]);

                // Create subscription record in our database
                $gymSubscription = GymPackageSubscription::create([
                    'gym_id' => $gym->id,
                    'package_id' => $this->selectedPackageId,
                    'stripe_session_id' => $checkoutSession->id,
                    'stripe_price_id' => $priceId,
                    'stripe_customer_id' => $gym->stripe_id,
                    'billing_cycle' => $this->selectedBillingCycle,
                    'starts_on' => now(),
                    'ends_on' => ($this->selectedBillingCycle === 'lifetime') ? null : ($this->selectedBillingCycle === 'monthly' ? now()->addDays(30) : now()->addDays(365)),
                    'expires_on' => ($this->selectedBillingCycle === 'lifetime') ? null : ($this->selectedBillingCycle === 'monthly' ? now()->addDays(30 + 7) : now()->addDays(365 + 7)),
                    'is_active' => false, // Will be set to true after payment confirmation
                    'amount' => $checkoutSession->amount_total / 100,
                    'status' => 'pending',
                ]);

                // Create payment record
                PackagePayment::create([
                    'gym_id' => $gym->id,
                    'package_id' => $this->selectedPackageId,
                    'subscription_id' => $gymSubscription->id,
                    'amount' => $gymSubscription->amount,
                    'payment_gateway' => 'stripe',
                    'payment_status' => 'pending',
                    'transaction_id' => 'STRIPE-' . uniqid(),
                    'remarks' => 'Stripe Payment for ' . $package->name,
                    'receipt_path' => $checkoutSession->url,
                ]);

                $this->closePaymentModal();
                return redirect($checkoutSession->url);

            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
                return null;
            }
        } elseif ($gateway === 'offline') {
            $this->showPaymentModal = false;
            $this->showOfflineReceiptModal = true;
        }
    }

    public function submitOfflineReceipt()
    {
        $this->validate();
        try {
            $gym = gym();
            $package = Package::findOrFail($this->selectedPackageId);
            $offlineMethod = GlobalPaymentGateway::findOrFail($this->selectedOfflineMethod);

            if ($package->package_type === 'lifetime') {
                $this->selectedBillingCycle = 'lifetime';
                $amount = $package->lifetime_price;
            } else {
                $amount = $this->selectedBillingCycle === 'monthly' 
                    ? $package->monthly_price
                    : $package->annual_price;
            }

            // Store the uploaded file
            $receiptPath = $this->offlineReceipt->store('offline_receipts', 'public');

            // Store in offline_payment_details table
            OfflinePaymentDetail::create([
                'global_payment_gateway_id' => $offlineMethod->id,
                'package_id' => $this->selectedPackageId,
                'gym_id' => $gym->id,
                'document_path' => $receiptPath,
                'description' => $this->offlineDescription,
                'amount' => $amount,
                'is_accepted' => false,
                'billing_cycle' => $this->selectedBillingCycle,
            ]);

            $this->showOfflineReceiptModal = false;
            $this->offlineReceipt = null;
            $this->offlineDescription = null;
            session()->flash('success', __('package.offline_payment_requested'));
            return redirect()->route('settings.billing.index');
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return null;
        }
    }

    public function closeOfflineReceiptModal()
    {
        $this->showOfflineReceiptModal = false;
        $this->offlineReceipt = null;
        $this->offlineDescription = null;
    }

    public function closeModal()
    {
        // Close both modals
        $this->showPaymentModal = false;
        $this->showOfflineReceiptModal = false;
        
        // Reset all related properties
        $this->selectedPackageId = null;
        $this->selectedPaymentGateway = null;
        $this->selectedOfflineMethod = null;
        $this->offlineReceipt = null;
        $this->offlineDescription = null;
    }

    public function chooseOfflineMethod($methodId)
    {
        $this->selectedOfflineMethod = $methodId;
        $this->showPaymentModal = false;
        $this->showOfflineReceiptModal = true;
    }

    public function render()
    {
        return view('livewire.settings.billing.upgrade-plan', [
            'packages' => $this->packages,
            'currentPackageId' => $this->currentPackageId,
            'currencies' => $this->currencies,
            'selectedCurrency' => $this->selectedCurrency,
            'offlinePaymentMethods' => $this->offlinePaymentMethods,
            'selectedOfflineMethod' => $this->selectedOfflineMethod,
            // 'selectedPaymentGateway' => $this->selectedPaymentGateway,
        ]);
    }
} 