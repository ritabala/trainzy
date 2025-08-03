<?php

namespace App\Livewire\Member;

use Livewire\Component;

class MemberManagement extends Component
{
    public function addMember()
    {
        return redirect()->route('members.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_members'), 403);
        return view('livewire.member.member-management');
    }
}
