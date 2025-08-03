<?php

namespace App\Livewire\Settings\StaffType;

use Livewire\Component;

class StaffTypeManagement extends Component
{
    public function addStaffType()
    {
        return redirect()->route('settings.staff_types.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('livewire.settings.staff-type.staff-type-management');
    }
} 