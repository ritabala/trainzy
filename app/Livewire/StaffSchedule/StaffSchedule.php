<?php

namespace App\Livewire\StaffSchedule;

use App\Models\User;
use App\Models\AvailableTimeSlot;
use Carbon\Carbon;
use Livewire\Component;

class StaffSchedule extends Component
{
    public $selectedStaff = null;
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];
    public $timeSlots = [];
    public $staffList = [];
    public $staffSearchQuery = '';
    public $filteredStaffList = [];

    protected $queryString = [
        'selectedStaff' => ['except' => ''],
        'currentMonth' => ['except' => ''],
        'currentYear' => ['except' => '']
    ];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->staffList = User::query()
            ->where('is_active', true)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]);
            })
            ->pluck('name', 'id')
            ->toArray();
        
        $this->filteredStaffList = $this->staffList;
        
        // Set staffSearchQuery if selectedStaff is provided
        if ($this->selectedStaff && isset($this->staffList[$this->selectedStaff])) {
            $this->staffSearchQuery = $this->staffList[$this->selectedStaff];
        }
        
        $this->updateCalendarDays();
    }

    public function updatedSelectedStaff()
    {
        $this->updateCalendarDays();
    }

    public function updatedStaffSearchQuery()
    {
        if (empty($this->staffSearchQuery) || is_null($this->staffSearchQuery)) {
            $this->filteredStaffList = $this->staffList;
            $this->selectedStaff = null;
            return;
        }

        $searchTerm = strtolower($this->staffSearchQuery);
        $this->filteredStaffList = collect($this->staffList)
            ->filter(function ($name) use ($searchTerm) {
                return str_contains(strtolower($name), $searchTerm);
            })
            ->toArray();
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

    public function updateCalendarDays()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
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
        
        $this->calendarDays = $days;
        $this->loadTimeSlots();
    }

    public function loadTimeSlots()
    {
        if (!$this->selectedStaff) {
            $this->timeSlots = collect();
            return;
        }
    
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $today = Carbon::today();
    
        // Get all active date-specific slots for the month
        $dateSpecificSlots = AvailableTimeSlot::with('activityClass')
            ->where('user_id', $this->selectedStaff)
            ->where('status', 'active')
            ->where('type', 'date_specific')
            ->whereBetween('date', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d')
            ])
            ->get();

        // Get current active weekly slots
        $currentWeeklySlots = AvailableTimeSlot::with('activityClass')
            ->where('user_id', $this->selectedStaff)
            ->where('status', 'active')
            ->where('type', 'weekly')
            ->get();

        // Get all historical weekly slots (inactive ones) for past dates
        $allHistoricalWeeklySlots = AvailableTimeSlot::with('activityClass')
            ->where('user_id', $this->selectedStaff)
            ->where('status', 'inactive')
            ->where('type', 'weekly')
            ->orderBy('created_at', 'desc')
            ->get();

        // Initialize the timeSlots collection
        $this->timeSlots = collect();

        // Group all date-specific slots by date
        $dateSpecificGrouped = $dateSpecificSlots->groupBy('date');

        // Process each day in the month
        $currentDate = $startOfMonth->copy();
        while ($currentDate <= $endOfMonth) {
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek;
            $today = Carbon::today();
            $isPast = $currentDate->lt($today);

            // Add all date-specific slots for this date (status 'active')
            if ($dateSpecificGrouped->has($dateString)) {
                foreach ($dateSpecificGrouped[$dateString] as $slot) {
                    $slotDate = Carbon::parse($slot->date);
                    $slot->is_disabled = $slotDate->lt($today);
                    $slot->date = $dateString;
                    $this->timeSlots->push($slot);
                }
            } else {
                // For past dates, use historical weekly slots
                if ($isPast) {
                    $historicalSlotsForDay = $allHistoricalWeeklySlots->where('day_of_week', $dayOfWeek);
                    // Group by created_at
                    $grouped = $historicalSlotsForDay->groupBy('created_at');
                    $activePatternDate = null;
                    foreach ($grouped as $createdAt => $slots) {
                        if (Carbon::parse($createdAt)->lte($currentDate)) {
                            $activePatternDate = $createdAt;
                            break;
                        }
                    }
                    if ($activePatternDate) {
                        foreach ($grouped[$activePatternDate] as $slot) {
                            $newSlot = clone $slot;
                            $newSlot->date = $dateString;
                            $newSlot->is_disabled = true;
                            $this->timeSlots->push($newSlot);
                        }
                    }
                } else {
                    // For current and future dates, use current weekly slots
                    $currentSlotsForDay = $currentWeeklySlots->where('day_of_week', $dayOfWeek);
                    foreach ($currentSlotsForDay as $slot) {
                        $newSlot = clone $slot;
                        $newSlot->date = $dateString;
                        $newSlot->is_disabled = false;
                        $this->timeSlots->push($newSlot);
                    }
                }
            }
            $currentDate->addDay();
        }

        // Group slots by date and sort by start time
        $this->timeSlots = $this->timeSlots->groupBy('date')->map(function($slots) {
            return $slots->sortBy('start_time');
        });
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff_schedule'), 403);
        return view('livewire.staff-schedule.staff-schedule');
    }
} 