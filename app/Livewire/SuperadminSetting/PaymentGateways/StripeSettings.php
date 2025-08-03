<?php

namespace App\Livewire\SuperAdminSetting\PaymentGateways;

use Livewire\Component;
use App\Models\GlobalPaymentGateway;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class StripeSettings extends Component
{   
    use LivewireAlert;

    public $stripeKey = '';
    public $stripeSecret = '';
    public $stripeWebhookKey = '';
    public $stripeStatus = false;
    public $stripeEnvironment = 'test';
    public $stripeData = [];

    protected $rules = [
        'stripeStatus' => 'boolean',
        'stripeEnvironment' => 'required|in:test,live',
        'stripeKey' => 'required_if:stripeStatus,true|string',
        'stripeSecret' => 'required_if:stripeStatus,true|string',
        'stripeWebhookKey' => 'nullable|string',
    ];

    protected function messages()
    {
        return [
            'stripeKey.required_if' => __('settings.payment_gateways.stripe.publishable_key_required'),
            'stripeSecret.required_if' => __('settings.payment_gateways.stripe.secret_key_required'),
        ];
    }

    public function mount()
    {
        $this->stripeData = GlobalPaymentGateway::first();

        if ($this->stripeData) {
            $this->stripeEnvironment = $this->stripeData->stripe_environment;
            $this->stripeStatus = $this->stripeData->stripe_status;

            if ($this->stripeData->stripe_environment === 'test') {
                $this->stripeKey = $this->stripeData->test_stripe_key ?? '';
                $this->stripeSecret = $this->stripeData->test_stripe_secret ?? '';
                $this->stripeStatus = (bool)$this->stripeData->stripe_status;
            } elseif ($this->stripeData->stripe_environment === 'live') {
                $this->stripeKey = $this->stripeData->live_stripe_key ?? '';
                $this->stripeSecret = $this->stripeData->live_stripe_secret ?? '';
                $this->stripeStatus = (bool)$this->stripeData->stripe_status;
            } 
        }
        else {
            $this->stripeStatus = false;
            $this->stripeData = new GlobalPaymentGateway();
        }
    }

    public function updatedStripeStatus($value)
    {
        $this->handleStripeStatusChange($value);
    }

    public function handleStripeStatusChange($value)
    {
        $this->stripeStatus = $value;
        $this->stripeData->stripe_status = $value;
    }

    public function updatedStripeEnvironment($value)
    {
        if ($this->stripeData) {                
            if ($value === 'test') {
                $this->stripeKey = $this->stripeData->test_stripe_key ?? '';
                $this->stripeSecret = $this->stripeData->test_stripe_secret ?? '';
                $this->stripeData->stripe_environment = 'test';
            } else {
                $this->stripeKey = $this->stripeData->live_stripe_key ?? '';
                $this->stripeSecret = $this->stripeData->live_stripe_secret ?? '';
                $this->stripeData->stripe_environment = 'live';
            }

        }
        $this->alert('info', __('settings.payment_gateways.stripe.messages.environment_changed'));
    }

    public function saveSettings()
    {

        $this->validate();

        try {
            if (!$this->stripeStatus && (!$this->stripeData->stripe_key && !$this->stripeData->stripe_secret && !$this->stripeData->test_stripe_key && !$this->stripeData->test_stripe_secret)) {
                $this->alert('error', __('settings.payment_gateways.stripe.messages.no_settings_found'));
                return;
            }
            
            if ($this->stripeEnvironment === 'test') {
                $this->stripeData->test_stripe_key = $this->stripeKey;
                $this->stripeData->test_stripe_secret = $this->stripeSecret;
            } else {
                $this->stripeData->live_stripe_key = $this->stripeKey;
                $this->stripeData->live_stripe_secret = $this->stripeSecret;
            }

            $this->stripeData->stripe_environment = $this->stripeEnvironment;
            $this->stripeData->stripe_status = $this->stripeStatus;

            $this->stripeData->save();            
            $this->alert('success', __('settings.payment_gateways.stripe.messages.settings_updated'));
        } catch (\Exception $e) {
            dd($e);
            $this->alert('error', __('settings.payment_gateways.stripe.messages.invalid_credentials'));
        }
    }

    public function render()
    {
        return view('livewire.superadmin-setting.payment-gateways.stripe-settings');
    }
} 