<?php

namespace App\Livewire\Payment;

use Livewire\Component;

class PaymentManagement extends Component
{
    public function addPayment()
    {
        return redirect()->route('payments.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_payments'), 403);
        return view('livewire.payment.payment-management');
    }
}
