<?php

namespace App\Livewire\Settings\Billing;

use Livewire\Component;
use App\Models\GymPackageSubscription;

class PurchaseHistory extends Component
{
    public $purchaseHistory;

    public function mount()
    {
        $this->loadPurchaseHistory();
    }

    public function loadPurchaseHistory()
    {
        $gym = gym();
        $this->purchaseHistory = GymPackageSubscription::with(['package', 'packagePayment'])
            ->where('gym_id', $gym->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.settings.billing.purchase-history');
    }
} 