<?php

namespace App\Livewire\Staff;

use Livewire\Component;

class StaffManagement extends Component
{
    public function addStaff()
    {
        return redirect()->route('staff.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff'), 403);
        return view('livewire.staff.staff-management');
    }
} 