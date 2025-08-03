<?php

namespace App\Livewire\Invoice;

use Livewire\Component;

class InvoiceManagement extends Component
{

    public function addInvoice()
    {
        return redirect()->route('invoices.create', ['from_add' => true]);
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_invoices'), 403);
        return view('livewire.invoice.invoice-management');
    }
}
