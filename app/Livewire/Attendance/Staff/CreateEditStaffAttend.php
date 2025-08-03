<?php

namespace App\Livewire\Attendance\Staff;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Collection;

class CreateEditStaffAttend extends Component
{
    public $selectedStaff = [];
    public $date;
    public $notes;
    public $attendance_id;
    public $staffSearchQuery = '';
    public $staff;
    public $isEditMode = false;

    protected $rules = [
        'selectedStaff' => 'required|array|min:1',
        'selectedStaff.*' => 'exists:users,id',
        'date' => 'required|date',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $attendance = Attendance::findOrFail($id);
            $this->attendance_id = $attendance->id;
            $this->selectedStaff = [$attendance->user_id];
            $this->date = \Carbon\Carbon::parse($attendance->check_in_at)->format('Y-m-d\TH:i');
            $this->notes = $attendance->notes;
        } else {
            $this->date = now()->format('Y-m-d\TH:i');
        }
        
        $this->loadStaff();
    }

    public function loadStaff()
    {
        if ($this->isEditMode) {
            $this->staff = User::where('id', $this->selectedStaff[0])->get();
            return;
        }

        $query = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]);
        });

        if ($this->staffSearchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->staffSearchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->staffSearchQuery . '%');
            });
        }

        $this->staff = $query->orderBy('name')->get();
    }

    public function updatedStaffSearchQuery()
    {
        $this->loadStaff();
    }

    public function toggleStaffSelection($staffId)
    {
        if ($this->isEditMode) {
            return;
        }

        if (in_array($staffId, $this->selectedStaff)) {
            $this->selectedStaff = array_diff($this->selectedStaff, [$staffId]);
        } else {
            $this->selectedStaff[] = $staffId;
        }
    }

    public function removeStaff($staffId)
    {
        if ($this->isEditMode) {
            return;
        }
        
        $this->selectedStaff = array_diff($this->selectedStaff, [$staffId]);
    }

    public function save()
    {
        $this->validate();

        if ($this->attendance_id) {
            // Update existing attendance
            $attendance = Attendance::findOrFail($this->attendance_id);
            $attendance->update([
                'user_id' => $this->selectedStaff[0],
                'check_in_at' => \Carbon\Carbon::parse($this->date)->format('Y-m-d H:i'),
                'notes' => $this->notes,
                'role_type' => 'staff',
            ]);
            session()->flash('message', __('members.attendance.updated'));
        } else {
            // Create multiple attendance records
            foreach ($this->selectedStaff as $staffId) {
                Attendance::create([
                    'user_id' => $staffId,
                    'check_in_at' => \Carbon\Carbon::parse($this->date)->format('Y-m-d H:i'),
                    'notes' => $this->notes,
                    'gym_id' => gym()->id,
                    'role_type' => 'staff',
                ]);
            }
            session()->flash('message', __('members.attendance.created'));
        }

        return redirect()->route('attendance.staff.index');
    }

    public function render()
    {
        return view('livewire.attendance.staff.create-edit-staff-attend');
    }
} 