<?php

namespace App\Livewire\ActivityClass;

use App\Models\ActivityClass;
use App\Models\AvailableTimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TimeSlotManagement extends Component
{
    use WithPagination, LivewireAlert;

    // View toggle
    public $view = 'list'; // 'list' or 'calendar'
    
    // Weekly hours management
    public $selectedDays = [];
    public $weeklyTimeSlots = [];
    public $showCopyTimesModal = false;
    public $copyFromDay = null;
    public $copyToDays = [];
    public $hasWeeklyChanges = false;
    public $originalWeeklyTimeSlots = [];
    
    // List view properties
    public $search = '';
    public $sortField = 'start_time';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // Calendar view properties
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];
    public $hasTimeSlots = [];
    public $isDateSpecific = [];
    public $selectedDate = null;
    
    // Date-specific hours properties
    public $showDateSpecificModal = false;
    public $modalYear;
    public $modalMonth;
    public $modalCalendarDays = [];
    public $selectedDates = [];
    public $dateSpecificTimeSlots = [];
    public $dateSpecificHours = [];
    public $editingWeeklyDay = null;
    public $hasDateSpecificChanges = false;
    public $modalSelectedDate = null;
    
    // Form properties
    public $showModal = false;
    public $modalAction = 'create';
    public $selectedTimeSlot = null;
    public $activityClassId;
    public $instructorId;
    public $startTime;
    public $endTime;
    public $status = 'active';
    
    // Filters
    public $selectedInstructor = null;
    public $dateRange = null;

    // Reset confirmation
    public $showResetConfirmation = false;
    public $resetDate = null;
    protected $listeners = ['resetToWeeklyHours'];


    protected $queryString = [
        'view' => ['except' => 'list'],
        'search' => ['except' => ''],
        'selectedInstructor' => ['except' => ''],
        'currentMonth' => ['except' => ''],
        'currentYear' => ['except' => '']
    ];

    protected $rules = [
        'activityClassId' => 'required|exists:activity_classes,id',
        'weeklyTimeSlots.*.*.start' => 'required|date_format:H:i',
        'weeklyTimeSlots.*.*.end' => 'required|date_format:H:i',
        'weeklyTimeSlots.*.*.instructor_id' => 'required|exists:users,id',
    ];

    public function messages()
    {
        return [
            'weeklyTimeSlots.*.*.start.required' => __('time_slots.start_time_required'),
            'weeklyTimeSlots.*.*.start.date_format' => __('time_slots.start_time_invalid'),
            'weeklyTimeSlots.*.*.end.required' => __('time_slots.end_time_required'),
            'weeklyTimeSlots.*.*.end.date_format' => __('time_slots.end_time_invalid'),
            'weeklyTimeSlots.*.*.instructor_id.required' => __('time_slots.instructor_required'),
            'weeklyTimeSlots.*.*.instructor_id.exists' => __('time_slots.instructor_not_found'),
            'dateSpecificTimeSlots.*.start.required' => __('time_slots.start_time_required'),
            'dateSpecificTimeSlots.*.start.date_format' => __('time_slots.start_time_invalid'),
            'dateSpecificTimeSlots.*.end.required' => __('time_slots.end_time_required'),
            'dateSpecificTimeSlots.*.end.date_format' => __('time_slots.end_time_invalid'),
            'dateSpecificTimeSlots.*.instructor_id.required' => __('time_slots.instructor_required'),
            'dateSpecificTimeSlots.*.instructor_id.exists' => __('time_slots.instructor_not_found'),
        ];
    }

    public function mount(ActivityClass $activityClass)
    {
        $this->activityClassId = $activityClass->id;
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->modalYear = $this->currentYear;
        $this->modalMonth = $this->currentMonth;
        
        // Initialize arrays
        $this->selectedDays = array_fill(0, 7, false);
        $this->weeklyTimeSlots = array_fill(0, 7, []);
        
        // Load existing data
        $this->loadExistingTimeSlots();
        $this->updateCalendarDays();
        $this->updateModalCalendarDays();
        $this->originalWeeklyTimeSlots = $this->weeklyTimeSlots;
    }

    public function loadExistingTimeSlots()
    {
        // Reset arrays
        $this->selectedDays = array_fill(0, 7, false);
        $this->weeklyTimeSlots = array_fill(0, 7, []);
        $this->dateSpecificHours = [];
        $this->hasTimeSlots = [];
        $this->isDateSpecific = [];

        // Load weekly time slots (both active and inactive for historical data)
        $existingWeeklySlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'weekly')
            ->whereIn('status', ['active', 'inactive'])
            ->get()
            ->groupBy('day_of_week');

        foreach ($existingWeeklySlots as $dayIndex => $slots) {
            // Only mark as selected if there are active slots
            $activeSlots = $slots->where('status', 'active');
            if ($activeSlots->isNotEmpty()) {
                $this->selectedDays[$dayIndex] = true;
                $this->weeklyTimeSlots[$dayIndex] = $activeSlots->map(function($slot) {
                    return [
                        'start' => Carbon::parse($slot->start_time)->format('H:i'),
                        'end' => Carbon::parse($slot->end_time)->format('H:i'),
                        'instructor_id' => $slot->user_id
                    ];
                })->toArray();
            }
        }

        // Load date-specific time slots
        $dateSpecificSlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'date_specific')
            ->where('status', 'active')
            ->get()
            ->groupBy(function($slot) {
                return Carbon::parse($slot->date)->format('Y-m-d');
            });

        foreach ($dateSpecificSlots as $date => $slots) {
            $this->dateSpecificHours[$date] = $slots->map(function($slot) {
                return [
                    'start' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end' => Carbon::parse($slot->end_time)->format('H:i'),
                    'instructor_id' => $slot->user_id
                ];
            })->toArray();
        }

        // Sort dateSpecificHours by date in descending order
        uksort($this->dateSpecificHours, function($a, $b) {
            return Carbon::parse($b)->timestamp <=> Carbon::parse($a)->timestamp;
        });

        // Update calendar view data
        $this->updateCalendarDays();
        
        // Dispatch an event to notify the frontend that data has been updated
        $this->dispatch('data-updated');
    }

    public function updatedView()
    {
        if ($this->view === 'calendar') {
            $this->updateCalendarDays();
        }
        $this->resetPage();
    }

    public function openModal($action = 'create', $timeSlotId = null)
    {
        $this->resetValidation();
        $this->modalAction = $action;
        
        if ($action === 'edit' && $timeSlotId) {
            $timeSlot = AvailableTimeSlot::findOrFail($timeSlotId);
            $this->selectedTimeSlot = $timeSlot;
            $this->activityClassId = $timeSlot->activity_class_id;
            $this->instructorId = $timeSlot->user_id;
            $this->startTime = $timeSlot->start_time->format('H:i');
            $this->endTime = $timeSlot->end_time->format('H:i');
            $this->status = $timeSlot->status;
        } else {
            $this->resetForm();
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedTimeSlot = null;
        $this->instructorId = null;
        $this->startTime = null;
        $this->endTime = null;
        $this->status = 'active';
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalAction === 'edit' && $this->selectedTimeSlot) {
                $this->selectedTimeSlot->update([
                    'activity_class_id' => $this->activityClassId,
                    'user_id' => $this->instructorId,
                    'start_time' => $this->startTime,
                    'end_time' => $this->endTime,
                    'status' => $this->status,
                ]);
                $this->alert('success', __('time_slots.time_slot_updated'));
            } else {
                AvailableTimeSlot::create([
                    'activity_class_id' => $this->activityClassId,
                    'user_id' => $this->instructorId,
                    'start_time' => $this->startTime,
                    'end_time' => $this->endTime,
                    'status' => $this->status,
                ]);
                $this->alert('success', __('time_slots.time_slot_created'));
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->alert('error', __('time_slots.time_slot_error_save', ['message' => $e->getMessage()]));
        }
    }

    public function delete($timeSlotId)
    {
        try {
            $timeSlot = AvailableTimeSlot::findOrFail($timeSlotId);
            $timeSlot->delete();
            $this->alert('success', __('time_slots.time_slot_deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('time_slots.time_slot_error_delete', ['message' => $e->getMessage()]));
        }
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->updateCalendarDays();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->updateCalendarDays();
    }

    public function previousModalMonth()
    {
        $date = Carbon::create($this->modalYear, $this->modalMonth, 1)->subMonth();
        $this->modalMonth = $date->month;
        $this->modalYear = $date->year;
        $this->updateModalCalendarDays();
    }

    public function nextModalMonth()
    {
        $date = Carbon::create($this->modalYear, $this->modalMonth, 1)->addMonth();
        $this->modalMonth = $date->month;
        $this->modalYear = $date->year;
        $this->updateModalCalendarDays();
    }

    public function updateCalendarDays()
    {
        $days = [];
        $daysInMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->daysInMonth;
        
        // Add empty days for the first week
        $firstDayOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $days[] = null;
        }
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $days[] = $day;
            
            $currentDate = Carbon::create($this->currentYear, $this->currentMonth, $day);
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek;
            $today = Carbon::today();
            
            // Check for date-specific slots
            $hasDateSpecificSlots = isset($this->dateSpecificHours[$dateString]) && !empty($this->dateSpecificHours[$dateString]);
            
            // Check for weekly slots based on whether it's past or current/future
            $hasWeeklySlots = false;
            if ($currentDate->lt($today)) {
                // For past dates, check historical (inactive) weekly slots
                $hasWeeklySlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                    ->where('type', 'weekly')
                    ->where('day_of_week', $dayOfWeek)
                    ->where('status', 'inactive')
                    ->exists();
            } else {
                // For current and future dates, check active weekly slots
                $hasWeeklySlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                    ->where('type', 'weekly')
                    ->where('day_of_week', $dayOfWeek)
                    ->where('status', 'active')
                    ->exists();
            }
            
            $this->hasTimeSlots[$day] = $hasDateSpecificSlots || $hasWeeklySlots;
            $this->isDateSpecific[$day] = $hasDateSpecificSlots;
        }
        
        // Add empty days to complete the last week
        $remainingDays = (ceil(count($days) / 7) * 7) - count($days);
        for ($i = 0; $i < $remainingDays; $i++) {
            $days[] = null;
        }
        
        $this->calendarDays = $days;
    }

    public function updateModalCalendarDays()
    {
        $date = Carbon::create($this->modalYear, $this->modalMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfWeek = $date->copy()->startOfMonth()->dayOfWeek;
        
        $days = [];
        
        // Add empty days for the start of the month
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $days[] = null;
        }
        
        // Add the days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $days[] = $day;
        }
        
        // Add empty days to complete the last week
        $remainingDays = (ceil(count($days) / 7) * 7) - count($days);
        for ($i = 0; $i < $remainingDays; $i++) {
            $days[] = null;
        }
        
        $this->modalCalendarDays = $days;
    }

    public function getTimeSlots()
    {
        $query = AvailableTimeSlot::query()
            ->with(['activityClass', 'instructor'])
            ->where('activity_class_id', $this->activityClassId);

        if ($this->view === 'calendar') {
            $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $today = Carbon::today();

            // Get all active date-specific slots for the month
            $dateSpecificSlots = $query->clone()
                ->where('type', 'date_specific')
                ->where('status', 'active')
                ->whereBetween('date', [
                    $startOfMonth->format('Y-m-d'),
                    $endOfMonth->format('Y-m-d')
                ])
                ->get();

            // Get current active weekly slots
            $currentWeeklySlots = $query->clone()
                ->where('type', 'weekly')
                ->where('status', 'active')
                ->get();

            // Get all historical weekly slots (inactive ones) for past dates
            $allHistoricalWeeklySlots = $query->clone()
                ->where('type', 'weekly')
                ->where('status', 'inactive')
                ->orderBy('created_at', 'desc')
                ->get();

            // Initialize the timeSlots collection
            $timeSlots = collect();

            // Add date-specific slots
            foreach ($dateSpecificSlots as $slot) {
                $slotDate = Carbon::parse($slot->date);
                $slot->is_disabled = $slotDate->lt($today);
                $timeSlots->push($slot);
            }

            // Process each day in the month
            $currentDate = $startOfMonth->copy();
            while ($currentDate <= $endOfMonth) {
                $dateString = $currentDate->format('Y-m-d');
                $dayOfWeek = $currentDate->dayOfWeek;
                
                // Check if there's already a date-specific slot for this date
                $hasDateSpecific = $dateSpecificSlots->contains(function($dateSlot) use ($dateString) {
                    return $dateSlot->date == $dateString;
                });
                
                if (!$hasDateSpecific) {
                    // For past dates, use historical weekly slots
                    if ($currentDate->lt($today)) {
                        // Find the weekly pattern that was active for this specific date
                        $historicalSlotsForDay = $allHistoricalWeeklySlots->where('day_of_week', $dayOfWeek);
                        
                        // Get the most recent pattern that was created before or on this date
                        $activePatternForDate = null;
                        foreach ($historicalSlotsForDay as $slot) {
                            $patternCreatedAt = Carbon::parse($slot->created_at);
                            if ($patternCreatedAt->lte($currentDate)) {
                                $activePatternForDate = $slot;
                                break; // This is the most recent pattern active for this date
                            }
                        }
                        
                        if ($activePatternForDate) {
                            $newSlot = clone $activePatternForDate;
                            $newSlot->date = $dateString;
                            $newSlot->is_disabled = true; // Past dates are always disabled
                            $timeSlots->push($newSlot);
                        }
                    } else {
                        // For current and future dates, use current weekly slots
                        $currentSlotsForDay = $currentWeeklySlots->where('day_of_week', $dayOfWeek);
                        foreach ($currentSlotsForDay as $slot) {
                            $newSlot = clone $slot;
                            $newSlot->date = $dateString;
                            $newSlot->is_disabled = false; // Current and future dates are not disabled
                            $timeSlots->push($newSlot);
                        }
                    }
                }
                
                $currentDate->addDay();
            }

            // Group slots by date and sort by start time
            return $timeSlots->groupBy('date')->map(function($slots) {
                return $slots->sortBy('start_time');
            });
        }

        return $query->where('status', 'active')->orderBy('start_time', 'asc')->get();
    }

    public function getActivityClassProperty()
    {
        return ActivityClass::find($this->activityClassId);
    }

    public function getInstructorsProperty()
    {
        return User::query()
        ->where('is_active', true)
        ->role(['staff-' . gym()->id, 'admin-' . gym()->id])
        ->get();
    }

    // Weekly hours management methods
    public function addTimeSlot($dayIndex)
    {
        // Auto-select the day if not selected
        if (!$this->selectedDays[$dayIndex]) {
            $this->selectedDays[$dayIndex] = true;
        }

        if (!isset($this->weeklyTimeSlots[$dayIndex])) {
            $this->weeklyTimeSlots[$dayIndex] = [];
        }

        // Default start time is 5 AM
        $startTime = '05:00';
        
        // If there are existing slots, find the next available time slot
        if (!empty($this->weeklyTimeSlots[$dayIndex])) {
            // Sort existing slots by start time
            usort($this->weeklyTimeSlots[$dayIndex], function($a, $b) {
                return $a['start'] <=> $b['start'];
            });
            
            // Find the first available gap
            $lastEndTime = '05:00';
            foreach ($this->weeklyTimeSlots[$dayIndex] as $slot) {
                if ($this->hasTimeGap($lastEndTime, $slot['start'], $this->activityClass->duration)) {
                    $startTime = $lastEndTime;
                    break;
                }
                $lastEndTime = $slot['end'];
            }
            
            // If no gap found, add after the last slot
            if ($startTime === '05:00') {
                $startTime = Carbon::createFromFormat('H:i', $lastEndTime)
                    ->addMinutes(0)
                    ->format('H:i');
            }
        }

        // Calculate end time based on activity class duration
        $endTime = Carbon::createFromFormat('H:i', $startTime)
            ->addMinutes($this->activityClass->duration)
            ->format('H:i');

        // Check if the new slot overlaps with existing slots
        if ($this->hasOverlap($dayIndex, $startTime, $endTime)) {
            $this->alert('error', __('time_slots.time_slot_overlap'));
            return;
        }

        $this->weeklyTimeSlots[$dayIndex][] = [
            'start' => $startTime,
            'end' => $endTime,
            'instructor_id' => '' // Add empty instructor_id by default
        ];

        // Re-sort slots after adding new one
        usort($this->weeklyTimeSlots[$dayIndex], function($a, $b) {
            return $a['start'] <=> $b['start'];
        });

        // Set hasWeeklyChanges to true when a slot is added
        $this->hasWeeklyChanges = true;
        
        // If editing a weekly day, set hasDateSpecificChanges to true
        if ($this->editingWeeklyDay !== null) {
            $this->hasDateSpecificChanges = true;
        }
    }

    protected function hasTimeGap($startTime, $endTime, $duration)
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);
        
        // Check if there's enough time for the activity duration plus 0 minutes buffer
        return $start->diffInMinutes($end) >= ($duration + 0);
    }

    protected function hasOverlap($dayIndex, $newStart, $newEnd)
    {
        $newStartTime = Carbon::createFromFormat('H:i', $newStart);
        $newEndTime = Carbon::createFromFormat('H:i', $newEnd);

        foreach ($this->weeklyTimeSlots[$dayIndex] as $slot) {
            $existingStart = Carbon::createFromFormat('H:i', $slot['start']);
            $existingEnd = Carbon::createFromFormat('H:i', $slot['end']);

            // Check if the new slot overlaps with existing slot
            if (
                ($newStartTime >= $existingStart && $newStartTime < $existingEnd) ||
                ($newEndTime > $existingStart && $newEndTime <= $existingEnd) ||
                ($newStartTime <= $existingStart && $newEndTime >= $existingEnd)
            ) {
                return true;
            }
        }

        return false;
    }

    public function removeTimeSlot($dayIndex, $slotIndex)
    {
        unset($this->weeklyTimeSlots[$dayIndex][$slotIndex]);
        $this->weeklyTimeSlots[$dayIndex] = array_values($this->weeklyTimeSlots[$dayIndex]);
        
        // If no slots left, uncheck the day
        if (empty($this->weeklyTimeSlots[$dayIndex])) {
            $this->selectedDays[$dayIndex] = false;
        }
        
        // Set hasWeeklyChanges to true when a slot is removed
        $this->hasWeeklyChanges = true;
        
        // If editing a weekly day, set hasDateSpecificChanges to true
        if ($this->editingWeeklyDay !== null) {
            $this->hasDateSpecificChanges = true;
        }
    }

    public function updateEndTime($dayIndex, $slotIndex)
    {
        if (isset($this->weeklyTimeSlots[$dayIndex][$slotIndex]['start']) && !empty($this->weeklyTimeSlots[$dayIndex][$slotIndex]['start'])) {
            $startTime = $this->weeklyTimeSlots[$dayIndex][$slotIndex]['start'];
            $endTime = Carbon::createFromFormat('H:i', $startTime)
                ->addMinutes($this->activityClass->duration)
                ->format('H:i');
            $this->weeklyTimeSlots[$dayIndex][$slotIndex]['end'] = $endTime;
        } else {
            // If start time is empty, clear the end time
            $this->weeklyTimeSlots[$dayIndex][$slotIndex]['end'] = '';
        }
    }

    public function openCopyTimesModal($fromDayIndex)
    {
        $this->copyFromDay = $fromDayIndex;
        $this->copyToDays = array_fill(0, 7, false);
        $this->copyToDays[$fromDayIndex] = null; // Set to null to indicate this day should be disabled
        $this->showCopyTimesModal = true;
    }

    public function closeCopyTimesModal()
    {
        $this->showCopyTimesModal = false;
        $this->copyFromDay = null;
        $this->copyToDays = [];
    }

    public function copyTimes()
    {
        if ($this->copyFromDay === null || empty($this->weeklyTimeSlots[$this->copyFromDay])) {
            $this->alert('error', __('time_slots.no_time_slots_to_copy'));
            return;
        }

        $copiedCount = 0;
        foreach ($this->copyToDays as $dayIndex => $shouldCopy) {
            if ($shouldCopy && $dayIndex !== $this->copyFromDay) {
                $this->selectedDays[$dayIndex] = true;
                $this->weeklyTimeSlots[$dayIndex] = $this->weeklyTimeSlots[$this->copyFromDay];
                $copiedCount++;
            }
        }

        if ($copiedCount > 0) {
            $this->alert('success', __('time_slots.time_slot_copied_to', ['count' => $copiedCount]));
            // Set hasWeeklyChanges to true when slots are copied
            $this->hasWeeklyChanges = true;
        } else {
            $this->alert('warning', __('time_slots.no_days_selected'));
        }

        $this->closeCopyTimesModal();
    }

    public function updatedWeeklyTimeSlots($value, $key)
    {
        // Extract the indices from the key (e.g., "0.0.start" or "0.0.instructor_id")
        $parts = explode('.', $key);
        if (count($parts) === 3) {
            $dayIndex = $parts[0];
            $slotIndex = $parts[1];
            $field = $parts[2];
            
            // Validate based on which field was updated
            switch ($field) {
                case 'start':
                    if (!empty($value)) {
                        $this->validate([
                            "weeklyTimeSlots.{$dayIndex}.{$slotIndex}.start" => 'date_format:H:i'
                        ]);
                        
                        // Auto-update end time based on activity class duration
                        if (isset($this->weeklyTimeSlots[$dayIndex][$slotIndex])) {
                            $endTime = Carbon::createFromFormat('H:i', $value)
                                ->addMinutes($this->activityClass->duration)
                                ->format('H:i');
                            $this->weeklyTimeSlots[$dayIndex][$slotIndex]['end'] = $endTime;
                        }
                    }
                    break;
                    
                case 'instructor_id':
                    if (isset($this->weeklyTimeSlots[$dayIndex][$slotIndex])) {
                        $this->validate([
                            "weeklyTimeSlots.{$dayIndex}.{$slotIndex}.instructor_id" => 'required|exists:users,id'
                        ]);
                        
                        // Verify the selected instructor has the Staff / Admin role
                        $instructor = User::find($value);
                        if ($instructor && (!$instructor->hasAnyRole(['staff-' . gym()->id, 'admin-' . gym()->id]) || !$instructor->is_active)) {
                            $this->addError("weeklyTimeSlots.{$dayIndex}.{$slotIndex}.instructor_id", __('time_slots.instructor_not_staff'));
                            return;
                        }                        
                    }
                    break;
            }

            // Set hasDateSpecificChanges to true when editing weekly day
            if ($this->editingWeeklyDay !== null) {
                $this->hasDateSpecificChanges = true;
            }
            
            // Set hasWeeklyChanges to true when any changes are made
            $this->hasWeeklyChanges = true;
        }
    }

    public function updatedDateSpecificTimeSlots($value, $key)
    {
        // Extract the slot index from the key
        $parts = explode('.', $key);
        if (count($parts) === 2) {
            $slotIndex = $parts[1];
            
            // If the start time field was updated, validate it only if it's not empty
            if (isset($this->dateSpecificTimeSlots[$slotIndex]['start'])) {
                $startTime = $this->dateSpecificTimeSlots[$slotIndex]['start'];
                if (!empty($startTime)) {
                    $this->validate([
                        'dateSpecificTimeSlots.'.$slotIndex.'.start' => 'date_format:H:i'
                    ]);
                }
            }
            
            // If the instructor_id field was updated, validate it
            if (isset($this->dateSpecificTimeSlots[$slotIndex]['instructor_id'])) {
                $this->validate([
                    'dateSpecificTimeSlots.'.$slotIndex.'.instructor_id' => 'required|exists:users,id'
                ]);
            }

            // Set hasDateSpecificChanges to true when any value changes
            $this->hasDateSpecificChanges = true;
        }
    }

    /**
     * Clean up future date-specific slots that conflict with new weekly patterns
     * This ensures that future dates follow the new weekly pattern
     */
    protected function cleanupFutureDateSpecificSlots($selectedDays, $weeklyTimeSlots, $editingDay = null)
    {
        $today = Carbon::today();
        
        $futureDateSpecificSlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'date_specific')
            ->where('date', '>=', $today->format('Y-m-d'))
            ->get();

        foreach ($futureDateSpecificSlots as $slot) {
            $slotDate = Carbon::parse($slot->date);
            $dayOfWeek = $slotDate->dayOfWeek;
            
            // If editing a specific day, only process that day
            if ($editingDay !== null && $dayOfWeek != $editingDay) {
                continue;
            }
            
            // Check if this day is now selected in weekly pattern
            if (!$selectedDays[$dayOfWeek]) {
                // If day is not selected, remove the date-specific slot
                $slot->delete();
            } else {
                // If day is selected, check if there's a matching weekly slot
                $hasMatchingWeeklySlot = false;
                foreach ($weeklyTimeSlots[$dayOfWeek] as $weeklySlot) {
                    if ($weeklySlot['start'] === $slot->start_time->format('H:i') && 
                        $weeklySlot['end'] === $slot->end_time->format('H:i') &&
                        $weeklySlot['instructor_id'] == $slot->user_id) {
                        $hasMatchingWeeklySlot = true;
                        break;
                    }
                }
                
                // If no matching weekly slot, remove the date-specific slot
                if (!$hasMatchingWeeklySlot) {
                    $slot->delete();
                }
            }
        }
    }

    public function saveWeeklyHours()
    {
        $this->validate();

        try {
            \DB::beginTransaction();
            
            $today = Carbon::today();
            
            // Mark existing weekly slots as inactive
            AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'weekly')
                ->where('status', 'active')
                ->update(['status' => 'inactive']);

            // Create new weekly slots
            foreach ($this->selectedDays as $dayIndex => $isSelected) {
                if ($isSelected && !empty($this->weeklyTimeSlots[$dayIndex])) {
                    foreach ($this->weeklyTimeSlots[$dayIndex] as $slot) {
                        // Skip if no instructor is selected
                        if (empty($slot['instructor_id'])) {
                            continue;
                        }

                        // Get the start and end times from the slot
                        $startTime = $slot['start'];
                        $endTime = $slot['end'];

                        // Check for instructor availability - only consider other weekly patterns
                        $hasOverlap = AvailableTimeSlot::where('user_id', $slot['instructor_id'])
                            ->where('status', 'active')
                            ->where('type', 'weekly')
                            ->where('day_of_week', $dayIndex)
                            ->where(function($query) use ($startTime, $endTime) {
                                $query->where(function($q) use ($startTime, $endTime) {
                                    $q->where('start_time', '<', $endTime)
                                      ->where('end_time', '>', $startTime);
                                });
                            })
                            ->exists();

                        if ($hasOverlap) {
                            throw new \Exception(__('time_slots.instructor_overlapping_time_slots'));
                        }

                        // Create the time slot - store only the weekly pattern
                        AvailableTimeSlot::create([
                            'activity_class_id' => $this->activityClassId,
                            'user_id' => $slot['instructor_id'],
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'type' => 'weekly',
                            'day_of_week' => $dayIndex,
                            'status' => 'active'
                        ]);
                    }
                } else {
                    // Remove all slots for unchecked days
                    AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                        ->where('type', 'weekly')
                        ->where('day_of_week', $dayIndex)
                        ->delete();
                }
            }

            // Remove date-specific slots for future dates that would conflict with new weekly pattern
            $this->cleanupFutureDateSpecificSlots($this->selectedDays, $this->weeklyTimeSlots, $this->editingWeeklyDay);

            \DB::commit();
            $this->alert('success', __('time_slots.weekly_hours_saved'));
            $this->loadExistingTimeSlots(); // Reload the data after saving

            // Update the original slots after saving
            $this->originalWeeklyTimeSlots = $this->weeklyTimeSlots;
            $this->hasWeeklyChanges = false;
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->alert('error', __('time_slots.weekly_hours_error_save', ['message' => $e->getMessage()]));
        }
    }

    public function openDateSpecificModal()
    {
        $this->selectedDates = [];
        $this->dateSpecificTimeSlots = [];
        $this->hasDateSpecificChanges = false;
        $this->modalYear = now()->year;
        $this->modalMonth = now()->month;
        $this->updateModalCalendarDays();
        $this->showDateSpecificModal = true;
        $this->modalSelectedDate = null;
    }

    public function closeDateSpecificModal()
    {
        $this->showDateSpecificModal = false;
        $this->editingWeeklyDay = null;
        $this->modalSelectedDate = null;
        // Don't clear selectedDates and dateSpecificTimeSlots here
    }

    public function toggleSelectedDate($date)
    {
        // If we're editing a specific date (modalSelectedDate is set), don't change the slots
        if ($this->modalSelectedDate) {
            $index = array_search($date, $this->selectedDates);
            if ($index !== false) {
                unset($this->selectedDates[$index]);
                $this->selectedDates = array_values($this->selectedDates);
            } else {
                $this->selectedDates[] = $date;
            }
            $this->hasDateSpecificChanges = true;
            return;
        }

        // Original behavior for when not editing a specific date
        $index = array_search($date, $this->selectedDates);
        if ($index !== false) {
            unset($this->selectedDates[$index]);
            $this->selectedDates = array_values($this->selectedDates);
            
            // If no dates are selected, clear the slots
            if (empty($this->selectedDates)) {
                $this->dateSpecificTimeSlots = [];
            }
        } else {
            // If this is the first date being selected, load its slots
            if (empty($this->selectedDates)) {
                $this->selectedDates[] = $date;
                $this->loadSlotsForDate($date);
            } else {
                // If other dates are already selected, just add the new date without changing slots
                $this->selectedDates[] = $date;
            }
        }
        $this->hasDateSpecificChanges = true;
    }

    protected function loadSlotsForDate($date)
    {
        // Load existing slots for the date if any
        $slots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'date_specific')
            ->where('date', $date)
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($slots->isNotEmpty()) {
            $this->dateSpecificTimeSlots = $slots->map(function($slot) {
                return [
                    'start' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end' => Carbon::parse($slot->end_time)->format('H:i'),
                    'instructor_id' => $slot->user_id
                ];
            })->toArray();
        } else {
            // If no existing slots, check if there are weekly slots for this day
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;
            $weeklySlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'weekly')
                ->where('day_of_week', $dayOfWeek)
                ->where('status', 'active')
                ->orderBy('start_time', 'asc')
                ->get();

            if ($weeklySlots->isNotEmpty()) {
                // Pre-populate with weekly slots
                $this->dateSpecificTimeSlots = $weeklySlots->map(function($slot) {
                    return [
                        'start' => Carbon::parse($slot->start_time)->format('H:i'),
                        'end' => Carbon::parse($slot->end_time)->format('H:i'),
                        'instructor_id' => $slot->user_id
                    ];
                })->toArray();
            } else {
                // If no weekly slots either, add a default slot
                $this->dateSpecificTimeSlots = [[
                    'start' => '05:00',
                    'end' => Carbon::createFromFormat('H:i', '05:00')
                        ->addMinutes($this->activityClass->duration)
                        ->format('H:i'),
                    'instructor_id' => ''
                ]];
            }
        }
    }

    public function addDateSpecificTimeSlot()
    {
        $startTime = '05:00';
        
        // If there are existing slots, find the next available time slot
        if (!empty($this->dateSpecificTimeSlots)) {
            // Sort existing slots by start time
            usort($this->dateSpecificTimeSlots, function($a, $b) {
                return $a['start'] <=> $b['start'];
            });
            
            // Get the last slot's end time
            $lastSlot = end($this->dateSpecificTimeSlots);
            $startTime = Carbon::createFromFormat('H:i', $lastSlot['end'])->format('H:i');
        }

        // Calculate end time based on activity class duration
        $endTime = Carbon::createFromFormat('H:i', $startTime)
            ->addMinutes($this->activityClass->duration)
            ->format('H:i');

        $this->dateSpecificTimeSlots[] = [
            'start' => $startTime,
            'end' => $endTime,
            'instructor_id' => ''
        ];

        $this->hasDateSpecificChanges = true;
    }

    public function removeDateSpecificTimeSlot($index)
    {
        unset($this->dateSpecificTimeSlots[$index]);
        $this->dateSpecificTimeSlots = array_values($this->dateSpecificTimeSlots);
        $this->hasDateSpecificChanges = true;
    }

    /**
     * Check if a date is in the past (before today)
     */
    protected function isDateInPast($date)
    {
        $checkDate = Carbon::parse($date)->startOfDay();
        $today = Carbon::now()->startOfDay();
        
        return $checkDate < $today;
    }

    public function saveDateSpecificHours()
    {
        // Validate the time slots
        $this->validate([
            'dateSpecificTimeSlots.*.start' => 'required|date_format:H:i',
            'dateSpecificTimeSlots.*.end' => 'required|date_format:H:i',
            'dateSpecificTimeSlots.*.instructor_id' => 'required|exists:users,id',
        ]);

        try {
            // Check if any selected date is in the past
            foreach ($this->selectedDates as $date) {
                if ($this->isDateInPast($date)) {
                    $this->alert('error', __('time_slots.past_date_error'));
                    return;
                }
            }

            // Save the time slots for each selected date
            foreach ($this->selectedDates as $date) {
                // Delete existing time slots for this date
                AvailableTimeSlot::where('activity_class_id', $this->activityClass->id)
                    ->where('type', 'date_specific')
                    ->where('date', $date)
                    ->delete();

                // Create new time slots
                foreach ($this->dateSpecificTimeSlots as $slot) {
                    AvailableTimeSlot::create([
                        'activity_class_id' => $this->activityClass->id,
                        'type' => 'date_specific',
                        'date' => $date,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'user_id' => $slot['instructor_id'],
                    ]);
                }
            }

            // Reload the data to ensure real-time updates
            $this->loadExistingTimeSlots();

            // Close the modal
            $this->showDateSpecificModal = false;
            $this->hasDateSpecificChanges = false;

            // Show success message
            $this->alert('success', __('time_slots.date_specific_hours_saved'));
        } catch (\Exception $e) {
            $this->alert('error', __('time_slots.date_specific_hours_error_save', ['message' => $e->getMessage()]));
        }
    }

    public function removeDateSpecificHours($date)
    {
        try {
            // Check if date is in the past
            if ($this->isDateInPast($date)) {
                $this->alert('error', __('time_slots.past_date_error'));
                return;
            }
            
            AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'date_specific')
                ->where('date', $date)
                ->delete();

            // Reload all data for real-time updates
            $this->loadExistingTimeSlots();

            $this->alert('success', __('time_slots.date_specific_hours_removed'));
        } catch (\Exception $e) {
            $this->alert('error', __('time_slots.date_specific_hours_error_remove', ['message' => $e->getMessage()]));
        }
    }

    public function editWeeklyDay($dayOfWeek)
    {
        $this->editingWeeklyDay = $dayOfWeek;
        
        // Auto-select the day if not selected
        $this->selectedDays[$dayOfWeek] = true;
        
        // Load existing weekly slots for this day
        $existingSlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'weekly')
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'active')
            ->get();

        if ($existingSlots->isNotEmpty()) {
            $this->weeklyTimeSlots[$dayOfWeek] = $existingSlots->map(function($slot) {
                return [
                    'start' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end' => Carbon::parse($slot->end_time)->format('H:i'),
                    'instructor_id' => $slot->user_id
                ];
            })->toArray();
        } else {
            // Initialize time slots if empty
            $this->weeklyTimeSlots[$dayOfWeek] = [];
            $this->addTimeSlot($dayOfWeek);
        }

        // Initialize hasDateSpecificChanges as false
        $this->hasDateSpecificChanges = false;
        $this->showDateSpecificModal = true;
    }

    public function editDateSpecificHours($date)
    {
        // Check if date is in the past
        if ($this->isDateInPast($date)) {
            $this->alert('error', __('time_slots.past_date_error'));
            return;
        }
        
        $this->selectedDates = [$date];
        $this->dateSpecificTimeSlots = [];
        $this->hasDateSpecificChanges = false;
        $this->modalSelectedDate = $date;
        // Load existing slots if any
        $slots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
            ->where('type', 'date_specific')
            ->where('date', Carbon::parse($date)->format('Y-m-d'))
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($slots->isNotEmpty()) {
            $this->dateSpecificTimeSlots = $slots->map(function($slot) {
                return [
                    'start' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end' => Carbon::parse($slot->end_time)->format('H:i'),
                    'instructor_id' => $slot->user_id
                ];
            })->toArray();
        } else {
            // If no existing slots, check if there are weekly slots for this day
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;
            $weeklySlots = AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'weekly')
                ->where('day_of_week', $dayOfWeek)
                ->where('status', 'active')
                ->orderBy('start_time', 'asc')
                ->get();

            if ($weeklySlots->isNotEmpty()) {
                // Pre-populate with weekly slots
                $this->dateSpecificTimeSlots = $weeklySlots->map(function($slot) {
                    return [
                        'start' => Carbon::parse($slot->start_time)->format('H:i'),
                        'end' => Carbon::parse($slot->end_time)->format('H:i'),
                        'instructor_id' => $slot->user_id
                    ];
                })->toArray();
            } else {
                // If no weekly slots either, add a default slot
                $this->dateSpecificTimeSlots[] = [
                    'start' => '05:00',
                    'end' => Carbon::createFromFormat('H:i', '05:00')
                        ->addMinutes($this->activityClass->duration)
                        ->format('H:i'),
                    'instructor_id' => ''
                ];
            }
        }

        $this->modalYear = Carbon::parse($date)->year;
        $this->modalMonth = Carbon::parse($date)->month;
        $this->updateModalCalendarDays();
        $this->showDateSpecificModal = true;
    }

    public function saveWeeklyDayHours()
    {
        try {
            // Validate the time slots
            $this->validate([
                'weeklyTimeSlots.'.$this->editingWeeklyDay.'.*.start' => 'required|date_format:H:i',
                'weeklyTimeSlots.'.$this->editingWeeklyDay.'.*.end' => 'required|date_format:H:i',
                'weeklyTimeSlots.'.$this->editingWeeklyDay.'.*.instructor_id' => 'required|exists:users,id',
            ]);
            
            \DB::beginTransaction();
            
            $today = Carbon::today();
            
            // Store the day name before nullifying it
            $dayName = __('time_slots.weekdays_long')[$this->editingWeeklyDay];
            
            // Only delete active slots for this day
            AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'weekly')
                ->where('day_of_week', $this->editingWeeklyDay)
                ->where('status', 'active')
                ->delete();
            
            // Create new time slots
            foreach ($this->weeklyTimeSlots[$this->editingWeeklyDay] as $slot) {
                AvailableTimeSlot::create([
                    'activity_class_id' => $this->activityClassId,
                    'user_id' => $slot['instructor_id'],
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'type' => 'weekly',
                    'day_of_week' => $this->editingWeeklyDay,
                    'status' => 'active'
                ]);
            }
            
            // Remove date-specific slots for future dates that would conflict with new weekly pattern
            $this->cleanupFutureDateSpecificSlots($this->selectedDays, $this->weeklyTimeSlots, $this->editingWeeklyDay);
            
            \DB::commit();
            
            // Reload data for real-time updates
            $this->loadExistingTimeSlots();
            
            // Close the modal and reset the editing day
            $this->showDateSpecificModal = false;
            $this->editingWeeklyDay = null;
            
            $this->alert('success', __('time_slots.weekly_schedule_updated', ['day' => $dayName]));
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->alert('error', __('time_slots.weekly_schedule_error_save', ['message' => $e->getMessage()]));
        }
    }

    public function openResetConfirmation($date)
    {
        if ($this->isDateInPast($date)) {
            $this->alert('error', __('time_slots.past_date_error'));
            return;
        }
        $this->resetDate = $date;
        $this->showResetConfirmation = true;
    }

    public function closeResetConfirmation()
    {
        $this->showResetConfirmation = false;
        $this->resetDate = null;
    }

    public function confirmResetToWeeklyHours($date)
    {
        if ($this->isDateInPast($date)) {
            $this->alert('error', __('time_slots.past_date_error'));
            return;
        }

        $this->alert('warning', __('time_slots.confirm_reset_to_weekly_hours'), [
            'text' => __('time_slots.confirm_reset_to_weekly_hours_text'),
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'resetToWeeklyHours',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('time_slots.reset_hours'),
            'data' => [
                'date' => $date,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function resetToWeeklyHours($data)
    {
        try {
            $date = $data['date'];
            // Delete date-specific slots for this date
            AvailableTimeSlot::where('activity_class_id', $this->activityClassId)
                ->where('type', 'date_specific')
                ->where('date', $date)
                ->delete();

            // Reload the data
            $this->loadExistingTimeSlots();

            $this->alert('success', __('time_slots.weekly_hours_reset'));
        } catch (\Exception $e) {
            $this->alert('error', __('time_slots.weekly_hours_error_reset', ['message' => $e->getMessage()]));
        }
    }

    public function updateDateSpecificEndTime($index)
    {
        if (isset($this->dateSpecificTimeSlots[$index]['start']) && !empty($this->dateSpecificTimeSlots[$index]['start'])) {
            $startTime = $this->dateSpecificTimeSlots[$index]['start'];
            $endTime = Carbon::createFromFormat('H:i', $startTime)
                ->addMinutes($this->activityClass->duration)
                ->format('H:i');
            $this->dateSpecificTimeSlots[$index]['end'] = $endTime;
            // Set hasDateSpecificChanges to true when end time is updated
            $this->hasDateSpecificChanges = true;
        } else {
            // If start time is empty, clear the end time
            $this->dateSpecificTimeSlots[$index]['end'] = '';
            // Set hasDateSpecificChanges to true when end time is cleared
            $this->hasDateSpecificChanges = true;
        }
    }

    public function selectDate($date)
    {
        if ($this->isDateInPast($date)) {
            return;
        }
        $this->selectedDate = $date;
        $this->dispatch('dateSelected', date: $date);
    }

    public function deselectDate()
    {
        $this->selectedDate = null;
    }

    public function render()
    {
        if ($this->view === 'calendar') {
            $this->updateCalendarDays();
        }

        return view('livewire.activity-class.time-slot-management', [
            'timeSlots' => $this->getTimeSlots(),
            'activityClass' => $this->activityClass,
            'instructors' => $this->instructors
        ]);
    }

    // Add new method to handle day selection changes
    public function updatedSelectedDays($value, $key)
    {
        $dayIndex = (int) $key;
        
        // If day is being checked
        if ($value) {
            // If no slots and we have original slots, restore them
            if (empty($this->weeklyTimeSlots[$dayIndex]) && 
                isset($this->originalWeeklyTimeSlots[$dayIndex]) && 
                !empty($this->originalWeeklyTimeSlots[$dayIndex])) {
                $this->weeklyTimeSlots[$dayIndex] = $this->originalWeeklyTimeSlots[$dayIndex];
            } 
            // If no slots and no original slots, add a default slot
            else if (empty($this->weeklyTimeSlots[$dayIndex])) {
                $this->addTimeSlot($dayIndex);
            }
        }
        
        // If day is being unchecked, mark as changed
        if (!$value) {
            $this->hasWeeklyChanges = true;
        }
    }
} 