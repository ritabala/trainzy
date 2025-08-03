<?php

namespace App\Livewire\SuperAdminSetting\PaymentGateways;

use Livewire\Component;

class PaymentGateways extends Component
{
    public $activeTab = 'stripe';

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.superadmin-setting.payment-gateways.payment-gateways');
    }
} 