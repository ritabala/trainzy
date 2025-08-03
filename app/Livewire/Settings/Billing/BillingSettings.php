<?php

namespace App\Livewire\Settings\Billing;

use Livewire\Component;

class BillingSettings extends Component
{
    public $activeTab = 'plan_details';

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.settings.billing.billing-settings');
    }
} 