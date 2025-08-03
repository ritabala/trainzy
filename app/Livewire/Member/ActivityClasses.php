<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\ActivityClass;
use App\Models\AvailableTimeSlot;
use App\Models\UserMembership;
use App\Models\MemberEnrollment;
use App\Models\MembershipActivityClass;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ActivityClasses extends Component
{
    use LivewireAlert;

    public $user;
    public $activityClassIds = [];
    public $editingActivity = false;
    public $selectedTimeSlots = [];
    public $modalActivityClass = null;
    public $latestMembership = null;
    public $timeSlotsData = [];
    public $showNoMembershipAssigned = false;
    public $timeSlotsByDay = [];

    public $dayNames = [];

    public function mount(User $user)
    {
        $this->dayNames = trans('members.weekdays_long');
        $this->user = $user;
        $this->loadActivityClasses();
    }

    public function loadActivityClasses()
    {
        $memberships = UserMembership::with(UserMembership::defaultEagerLoad())
            ->where('user_id', $this->user->id)
            ->orderByDesc('membership_start_date')
            ->get();

        $this->latestMembership = $memberships->firstWhere('membership_status', 'active') ?? $memberships->first();

        if (!$this->latestMembership) {
            $this->showNoMembershipAssigned = true;
            $this->activityClassIds = [];
            return;
        }

        $this->activityClassIds = MembershipActivityClass::where('membership_id', $this->latestMembership->membership_id)
            ->pluck('activity_class_id')
            ->toArray();
    }

    public function openEditModal($activityId)
    {
        $this->editingActivity = true;
        $this->selectedTimeSlots = [];
        $this->timeSlotsData = [];
        $this->timeSlotsByDay = [];
        
        // Load activity class with filtered and ordered available time slots
        $this->modalActivityClass = ActivityClass::with(['availableTimeSlots' => function ($query) {
            $query->where('type', 'weekly')
                  ->where('status', 'active')
                  ->orderBy('day_of_week')
                  ->orderBy('start_time');
        }])->find($activityId);

        // Get selected slot IDs for this user and activity
        $selectedSlotIds = MemberEnrollment::where('member_enrollments.user_id', $this->user->id)
            ->join('available_time_slots', 'member_enrollments.available_time_slot_id', '=', 'available_time_slots.id')
            ->where('available_time_slots.activity_class_id', $activityId)
            ->pluck('member_enrollments.available_time_slot_id')
            ->toArray();

        // Map and prepare the time slots data, marking selected slots
        $this->timeSlotsData = $this->modalActivityClass->availableTimeSlots->map(function ($slot) use ($selectedSlotIds) {
            $isSelected = in_array($slot->id, $selectedSlotIds);
            if ($isSelected) {
                $this->selectedTimeSlots[$slot->id] = true;
            }
            return [
                'id' => $slot->id,
                'is_selected' => $isSelected,
                'day_of_week' => $slot->day_of_week,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
            ];
        })->toArray();

        // Pre-group time slots by day_of_week for optimized blade rendering
        $grouped = collect($this->timeSlotsData)
            ->groupBy('day_of_week')
            ->map(function ($slots) {
                return $slots->sortBy('start_time')->values()->all();
            });
    
        $this->timeSlotsByDay = $grouped->toArray();
    }

    protected function updateModalTimeSlots()
    {
        if ($this->modalActivityClass) {
            $this->modalActivityClass->availableTimeSlots = collect($this->timeSlotsData)->map(function ($slot) {
                return (object)$slot;
            });
        }
    }

    public function closeEditModal()
    {
        $this->editingActivity = false;
        $this->modalActivityClass = null;
        $this->selectedTimeSlots = [];
        $this->timeSlotsData = [];
    }

    public function closeModal()
    {
        $this->closeEditModal();
    }

    public function hydrate()
    {
        if ($this->modalActivityClass) {
            $this->updateModalTimeSlots();
        }
    }

    public function toggleTimeSlot($slotId)
    {
        // Find the day and index of the clicked slot in the grouped array
        foreach ($this->timeSlotsByDay as $day => &$slots) {
            foreach ($slots as $index => $slot) {
                if ($slot['id'] == $slotId) {
                    $wasSelected = $slot['is_selected'];
    
                    // Unselect all slots of this day
                    foreach ($slots as $i => $_slot) {
                        $slots[$i]['is_selected'] = false;
                    }
    
                    // If it was not selected, select it now
                    if (!$wasSelected) {
                        $slots[$index]['is_selected'] = true;
                    }
    
                    // Update the corresponding entry in timeSlotsData for consistency
                    foreach ($this->timeSlotsData as $k => $dataSlot) {
                        if ($dataSlot['day_of_week'] == $day) {
                            $this->timeSlotsData[$k]['is_selected'] = false;
                        }
                        if ($dataSlot['id'] == $slotId && !$wasSelected) {
                            $this->timeSlotsData[$k]['is_selected'] = true;
                        }
                    }
    
                    // Update modalActivityClass availableTimeSlots for UI sync (optional)
                    $flatSlots = [];
                    foreach ($this->timeSlotsByDay as $daySlots) {
                        $flatSlots = array_merge($flatSlots, $daySlots);
                    }
                    $this->modalActivityClass->availableTimeSlots = collect($flatSlots)->map(fn($slot) => (object)$slot);
    
                    return; // Exit after processing found slot
                }
            }
        }
    }

    protected function checkForOverlaps()
    {
       // Get all existing enrollments for the user
       $existingEnrollments = MemberEnrollment::where('member_enrollments.user_id', $this->user->id)
           ->join('available_time_slots', 'member_enrollments.available_time_slot_id', '=', 'available_time_slots.id')
           ->where('available_time_slots.type', 'weekly')
           ->where('available_time_slots.status', 'active')
           ->select('available_time_slots.*')
           ->get();

        // Check for overlaps with selected slots
        foreach ($this->timeSlotsData as $slot) {
            if ($slot['is_selected']) {
                $slotStart = strtotime($slot['start_time']);
                $slotEnd = strtotime($slot['end_time']);
                $slotDay = $slot['day_of_week'];

                foreach ($existingEnrollments as $existing) {
                    // Skip if it's the same slot we're trying to save
                    if ($existing->id == $slot['id']) continue;

                    // Check if it's the same day
                    if ($existing->day_of_week == $slotDay) {
                        $existingStart = strtotime($existing->start_time);
                        $existingEnd = strtotime($existing->end_time);

                        // Check for time overlap
                        if (($slotStart < $existingEnd) && ($slotEnd > $existingStart)) {
                            $this->alert('error', __('activity.time_slot_overlap'));
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    public function saveSchedule()
    {
        if (!$this->editingActivity || !$this->checkForOverlaps()) {
            return;
        }

        // If no overlaps, proceed with saving
        foreach ($this->timeSlotsData as $slot) {
            if ($slot['is_selected']) {
                MemberEnrollment::firstOrCreate([
                    'user_id' => $this->user->id,
                    'available_time_slot_id' => $slot['id'],
                    'enrolled_at' => now(),
                    'status' => 'enrolled',
                ]);
            } else {
                MemberEnrollment::where('user_id', $this->user->id)
                    ->where('available_time_slot_id', $slot['id'])
                    ->delete();
            }
        }

        $this->alert('success', __('activity.updated_schedule_success', ['name' => $this->modalActivityClass->name]));
        $this->closeEditModal();
    }

    public function render()
    {
        $activityClasses = ActivityClass::whereIn('id', $this->activityClassIds)
            ->with(['availableTimeSlots' => function ($query) {
                $query->where('type', 'weekly')->where('status', 'active');
            }])->get();

        $selectedSlotIds = MemberEnrollment::where('user_id', $this->user->id)
            ->pluck('available_time_slot_id')
            ->toArray();

        foreach ($activityClasses as $activityClass) {
            foreach ($activityClass->availableTimeSlots as $slot) {
                $slot->is_selected = in_array($slot->id, $selectedSlotIds);
            }
        }

        return view('livewire.member.activity-classes', [
            'activityClasses' => $activityClasses,
            'dayNames' => $this->dayNames,
        ]);
    }
}
