<?php

namespace App\Livewire\Attendance\Staff;

use Livewire\Component;
use App\Models\Attendance;

class ViewStaffAttend extends Component
{
    public $attendance;
    public $id;

    public function mount($id)
    {
        $this->id = $id;
        $this->attendance = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]);
                });
            })
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.attendance.staff.view-staff-attend', [
            'attendance' => $this->attendance,
        ]);
    }
} 