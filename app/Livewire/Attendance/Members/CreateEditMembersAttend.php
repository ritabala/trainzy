<?php

namespace App\Livewire\Attendance\Members;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Collection;

class CreateEditMembersAttend extends Component
{
    public $selectedMembers = [];
    public $date;
    public $notes;
    public $attendance_id;
    public $memberSearchQuery = '';
    public $members;
    public $isEditMode = false;

    protected $rules = [
        'selectedMembers' => 'required|array|min:1',
        'selectedMembers.*' => 'exists:users,id',
        'date' => 'required|date',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $attendance = Attendance::findOrFail($id);
            $this->attendance_id = $attendance->id;
            $this->selectedMembers = [$attendance->user_id];
            $this->date = \Carbon\Carbon::parse($attendance->check_in_at)->format('Y-m-d\TH:i');
            $this->notes = $attendance->notes;
        } else {
            $this->date = now()->format('Y-m-d\TH:i');
        }
        
        $this->loadMembers();
    }

    public function loadMembers()
    {
        if ($this->isEditMode) {
            $this->members = User::where('id', $this->selectedMembers[0])->get();
            return;
        }

        $query = User::whereHas('roles', function($query) {
            $query->where('name', 'member-' . gym()->id);
        });

        if ($this->memberSearchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->memberSearchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->memberSearchQuery . '%');
            });
        }

        $this->members = $query->orderBy('name')->get();
    }

    public function updatedMemberSearchQuery()
    {
        $this->loadMembers();
    }

    public function toggleMemberSelection($memberId)
    {
        if ($this->isEditMode) {
            return;
        }

        if (in_array($memberId, $this->selectedMembers)) {
            $this->selectedMembers = array_diff($this->selectedMembers, [$memberId]);
        } else {
            $this->selectedMembers[] = $memberId;
        }
    }

    public function removeMember($memberId)
    {
        if ($this->isEditMode) {
            return;
        }
        
        $this->selectedMembers = array_diff($this->selectedMembers, [$memberId]);
    }

    public function save()
    {
        $this->validate();

        if ($this->attendance_id) {
            // Update existing attendance
            $attendance = Attendance::findOrFail($this->attendance_id);
            $attendance->update([
                'user_id' => $this->selectedMembers[0],
                'check_in_at' => \Carbon\Carbon::parse($this->date)->format('Y-m-d H:i'),
                'notes' => $this->notes,
                'role_type' => 'member',
        ]);
            session()->flash('message', __('members.attendance.updated'));
        } else {
            // Create multiple attendance records
            foreach ($this->selectedMembers as $memberId) {
                Attendance::create([
                    'user_id' => $memberId,
                    'gym_id' => gym()->id,
                    'check_in_at' => \Carbon\Carbon::parse($this->date)->format('Y-m-d H:i'),
                    'notes' => $this->notes,
                    'role_type' => 'member',
                ]);
            }
            session()->flash('message', __('members.attendance.created'));
        }

        return redirect()->route('attendance.members.index');
    }

    public function render()
    {
        return view('livewire.attendance.members.create-edit-members-attend');
    }
} 