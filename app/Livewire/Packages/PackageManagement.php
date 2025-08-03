<?php

namespace App\Livewire\Packages;

use Livewire\Component;

class PackageManagement extends Component
{
    public function addPackage()
    {
        return redirect()->route('packages.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_packages'), 403);
        return view('livewire.packages.package-management');
    }
} 