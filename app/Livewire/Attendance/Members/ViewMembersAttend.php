<?php

namespace App\Livewire\Attendance\Members;

use Livewire\Component;
use App\Models\Attendance;

class ViewMembersAttend extends Component
{
    public $attendance;
    public $id;

    public function mount($id)
    {
        $this->id = $id;
        $this->attendance = Attendance::with('user')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.attendance.members.view-members-attend', [
            'attendance' => $this->attendance,
        ]);
    }
} 