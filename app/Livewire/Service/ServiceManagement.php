<?php

namespace App\Livewire\Service;

use Livewire\Component;

class ServiceManagement extends Component
{
    public function addService()
    {
        return redirect()->route('services.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_services'), 403);
        return view('livewire.service.service-management');
    }
}
