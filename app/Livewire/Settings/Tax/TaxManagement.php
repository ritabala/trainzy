<?php

namespace App\Livewire\Settings\Tax;

use Livewire\Component;

class TaxManagement extends Component
{
    public function addTax()
    {
        return redirect()->route('settings.taxes.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('livewire.settings.tax.tax-management');
    }
}
