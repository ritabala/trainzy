<?php

namespace App\Livewire\Attendance\Members;

use Livewire\Component;

class MembersAttendMgmt extends Component
{
    public function addMemberAttend()
    {
        return redirect()->route('attendance.members.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_member_attendance'), 403);
        return view('livewire.attendance.members.members-attend-mgmt');
    }
}
