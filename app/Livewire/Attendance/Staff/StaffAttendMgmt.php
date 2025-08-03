<?php

namespace App\Livewire\Attendance\Staff;

use Livewire\Component;

class StaffAttendMgmt extends Component
{
    public function addStaffAttend()
    {
        return redirect()->route('attendance.staff.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff_attendance'), 403);
        return view('livewire.attendance.staff.staff-attend-mgmt');
    }
} 