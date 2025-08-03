<?php

namespace App\Livewire\Membership;

use App\Models\Membership;
use Livewire\Component;

class MembershipManagement extends Component
{
    public function addMembership()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_membership'), 403);
        return redirect()->route('memberships.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_memberships'), 403);
        return view('livewire.membership.membership-management');
    }
}
