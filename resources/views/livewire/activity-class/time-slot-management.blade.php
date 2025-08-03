<div>
    <div
        x-data="{ 
            showDropdown: false,
            selectedDay: null,
            previousSelectedDate: null,
            init() {
                // Setup listeners for data updates
                Livewire.on('data-updated', () => {
                    // Force Alpine to recognize the data changes
                    this.$nextTick(() => {
                        // Update any Alpine data here if needed
                    });
                });

                // Listen for date selections
                Livewire.on('dateSelected', (date) => {
                    // Close previous dropdown if a new date is selected
                    if (this.previousSelectedDate && this.previousSelectedDate !== date) {
                        this.showDropdown = false;
                        setTimeout(() => {
                            this.showDropdown = true;
                            this.previousSelectedDate = date;
                        }, 10);
                    } else {
                        this.showDropdown = true;
                        this.previousSelectedDate = date;
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    const isDropdownClick = e.target.closest('.calendar-day-dropdown');
                    const isModalClick = e.target.closest('.modal-content');
                    
                    if (!isDropdownClick && !isModalClick) {
                        this.showDropdown = false;
                        @this.deselectDate();
                    }
                });
            }
        }"
    >
        <div class="flex flex-col lg:flex-row justify-between items-center px-4 py-4">
            <div class="mb-4 lg:mb-0">
                <h2 class="text-xl font-semibold text-gray-800 leading-tight dark:text-gray-200">
                    {{ __('time_slots.schedule') }} : {{ $activityClass->name }}
                </h2>
            </div>
            <a href="{{ route('activity-classes.index') }}" 
                class="w-full lg:w-auto px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm text-center">
                    {{ __('time_slots.back_to_activity_classes') }}
            </a>
        </div>

        <div class="py-4">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 ">
                <!-- View Toggle -->
                <div class="mb-4 hidden lg:block">
                    <div class="inline-flex rounded-lg shadow-sm bg-gray-100 p-1 dark:bg-gray-700 w-full sm:w-auto justify-center">
                        <button wire:click="$set('view', 'list')" 
                            class="flex items-center px-4 py-2 text-sm {{ $view === 'list' ? 'bg-white rounded-md shadow text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('time_slots.list_view') }}
                        </button>
                        <button wire:click="$set('view', 'calendar')" 
                            class="flex items-center px-4 py-2 text-sm {{ $view === 'calendar' ? 'bg-white rounded-md shadow text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('time_slots.calendar_view') }}
                        </button>
                    </div>
                </div>

                @if($view === 'list')
                    <!-- List View - Responsive Grid Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Weekly Hours Column -->
                        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="flex justify-between items-center p-4">
                                <h3 class="text-lg font-semibold">{{ __('time_slots.weekly_hours') }}</h3>
                                <button wire:click="saveWeeklyHours" 
                                    class="px-3 py-1.5 {{ $hasWeeklyChanges ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }} text-white rounded-md text-sm"
                                    {{ !$hasWeeklyChanges ? 'disabled' : '' }}>
                                    {{ __('time_slots.save_weekly_hours') }}
                                </button>
                            </div>
                            
                            <!-- Days of Week -->
                            <div class="overflow-visible pr-2">
                                @foreach(__('time_slots.weekdays_short') as $index => $day)
                                <div class="p-3">
                                    <div class="flex items-center justify-between">
                                        <!-- Day Checkbox and Label -->
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                wire:model="selectedDays.{{ $index }}"
                                                class="rounded border-gray-300 text-blue-600  shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm font-bold text-gray-700 dark:text-gray-200">{{ $day }}</span>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            <button wire:click="addTimeSlot({{ $index }})"
                                                class="text-gray-600 dark:text-gray-200  dark:hover:bg-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-md p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="openCopyTimesModal({{ $index }})"
                                                class="text-gray-600 dark:text-gray-200  dark:hover:bg-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-md p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Time Slots Container -->
                                    <div class="mt-2 ml-6">
                                        @if(!empty($selectedDays[$index]))
                                            <div class="space-y-2">
                                                @foreach($weeklyTimeSlots[$index] ?? [] as $slotIndex => $slot)
                                                <div class="flex flex-col lg:flex-row lg:items-center gap-2">
                                                    <!-- Start Time -->
                                                    <div class="relative lg:w-26">
                                                        <input type="time" 
                                                            wire:model="weeklyTimeSlots.{{ $index }}.{{ $slotIndex }}.start"
                                                            wire:change="updateEndTime({{ $index }}, {{ $slotIndex }})"
                                                            class="w-full rounded-md border {{ $errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.start') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-white pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200 dark:bg-gray-800">
                                                        <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                            <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    
                                                    <span class="text-gray-500 text-sm font-medium hidden lg:block">-</span>
                                                    
                                                    <!-- End Time -->
                                                    <div class="relative lg:w-26">
                                                        <input type="time" 
                                                            wire:model="weeklyTimeSlots.{{ $index }}.{{ $slotIndex }}.end"
                                                            readonly
                                                            disabled
                                                            class="w-full rounded-md border {{ $errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.end') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200 dark:bg-gray-700">
                                                        <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                            <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Instructor Select -->
                                                    <div class="lg:w-45 relative">
                                                        <x-dropdown align="left" width="50">
                                                            <x-slot name="trigger">
                                                                <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border {{ $errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.instructor_id') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                                                                    @if(isset($weeklyTimeSlots[$index][$slotIndex]['instructor_id']) && $weeklyTimeSlots[$index][$slotIndex]['instructor_id'])
                                                                        {{ $instructors->find($weeklyTimeSlots[$index][$slotIndex]['instructor_id'])->name }}
                                                                    @else
                                                                        {{ __('time_slots.select_instructor') }}
                                                                    @endif
                                                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </x-slot>

                                                            <x-slot name="content">
                                                                <div class="py-1 dark:border dark:border-gray-700">
                                                                    <x-dropdown-link wire:click="$set('weeklyTimeSlots.{{ $index }}.{{ $slotIndex }}.instructor_id', '')" 
                                                                        :selected="!isset($weeklyTimeSlots[$index][$slotIndex]['instructor_id']) || $weeklyTimeSlots[$index][$slotIndex]['instructor_id'] === ''"
                                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                        {{ __('time_slots.select_instructor') }}
                                                                    </x-dropdown-link>
                                                                    @foreach($instructors as $instructor)
                                                                        <x-dropdown-link wire:click="$set('weeklyTimeSlots.{{ $index }}.{{ $slotIndex }}.instructor_id', '{{ $instructor->id }}')" 
                                                                            :selected="isset($weeklyTimeSlots[$index][$slotIndex]['instructor_id']) && $weeklyTimeSlots[$index][$slotIndex]['instructor_id'] == $instructor->id"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                            {{ $instructor->name }}
                                                                        </x-dropdown-link>
                                                                    @endforeach
                                                                </div>
                                                            </x-slot>
                                                        </x-dropdown>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <button wire:click="removeTimeSlot({{ $index }}, {{ $slotIndex }})"
                                                        class="text-red-400 hover:text-red-600 p-1 inline-flex items-center gap-1 justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        <span class="lg:hidden">Remove</span>
                                                    </button>
                                                </div>
                                                <!-- Error messages for the entire row -->
                                                <div class="ml-6">
                                                    @if ($errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.start') ||
                                                        $errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.end') ||
                                                        $errors->has('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.instructor_id'))
                                                        <p class="mt-1 text-xs text-red-600">
                                                            {{ $errors->first('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.start') }}
                                                            {{ $errors->first('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.end') }} 
                                                            {{ $errors->first('weeklyTimeSlots.'.$index.'.'.$slotIndex.'.instructor_id') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Date-specific Hours Column -->
                        <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 gap-2">
                                <h3 class="text-lg font-semibold">{{ __('time_slots.date_specific_hours') }}</h3>
                                <button wire:click="openDateSpecificModal" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    {{ __('time_slots.add_date_specific_hours') }}
                                </button>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 dark:text-gray-400">
                                {{ __('time_slots.date_specific_hours_description') }}
                            </p>

                            <!-- Date-specific Hours List -->
                            <div class=" pr-2">
                                <div class="space-y-4">
                                    @forelse($dateSpecificHours as $date => $slots)
                                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                                            <div class="flex justify-between items-start mb-2 dark:text-white">
                                                <h4 class="font-medium">{{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</h4>
                                                <div class="flex space-x-2">
                                                <button wire:click="editDateSpecificHours('{{ $date }}')" 
                                                    class="text-blue-600 hover:text-blue-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button wire:click="removeDateSpecificHours('{{ $date }}')" 
                                                    class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @foreach($slots as $slot)
                                            <div class="text-sm text-gray-600 bg-white p-2 rounded mb-1 dark:bg-gray-700 dark:text-gray-200">
                                                {{ \Carbon\Carbon::parse($slot['start'])->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($slot['end'])->format('g:i A') }}
                                                <span class="ml-2 text-gray-800 dark:text-gray-200">{{ $instructors->find($slot['instructor_id'])->name }}</span>
                                            </div>
                                        @endforeach
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-sm dark:text-gray-400">{{ __('time_slots.no_date_specific_hours_set') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Calendar View -->
                    <div class="bg-white rounded-lg shadow p-4 sm:p-6 dark:bg-gray-800">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
                            <button wire:click="previousMonth" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                                {{ __('time_slots.previous_month') }}
                            </button>
                            <h3 class="text-lg font-semibold">
                                {{ Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                            </h3>
                            <button wire:click="nextMonth" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md text-sm dark:bg-gray-800 dark:border dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                                {{ __('time_slots.next_month') }}
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 cursor-default overflow-x-auto">
                            @foreach(__('time_slots.weekdays_short') as $dayName)
                                <div class="text-center font-semibold py-2 text-sm min-w-[80px]">{{ $dayName }}</div>
                            @endforeach

                            @foreach($calendarDays as $day)
                                @php
                                    $currentDate = $day ? Carbon\Carbon::create($currentYear, $currentMonth, $day) : null;
                                    $isDatePast = $currentDate ? $currentDate->startOfDay() < Carbon\Carbon::now()->startOfDay() : false;
                                    $isPastMonth = $currentYear < Carbon\Carbon::now()->year || 
                                                        ($currentYear === Carbon\Carbon::now()->year && $currentMonth < Carbon\Carbon::now()->month);
                                    $hasDateSpecific = $currentDate && !$isPastMonth ? isset($isDateSpecific[$day]) && $isDateSpecific[$day] : false;
                                    $hasSlots = $currentDate && !$isPastMonth ? isset($hasTimeSlots[$day]) && $hasTimeSlots[$day] : false;
                                @endphp
                                <div class="relative border dark:border-gray-700 p-2 min-h-[120px] min-w-[80px] w-full calendar-day-dropdown {{ $day ? '' : 'bg-gray-100 dark:bg-gray-600' }} {{ $isDatePast || $isPastMonth ? '' : ($day ? ' hover:bg-blue-50 hover:border-blue-500 hover:cursor-pointer dark:hover:bg-blue-900 dark:hover:border-blue-200' : '') }} {{ $selectedDate === $currentYear . '-' . $currentMonth . '-' . $day ? 'ring-2 ring-blue-500' : '' }}"
                                    @if($day && !$isDatePast && !$isPastMonth)
                                        @click="$wire.selectDate('{{ $currentYear }}-{{ $currentMonth }}-{{ $day }}')"
                                    @endif>
                                    @if($day)
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center space-x-1">
                                                <span class="font-semibold text-sm {{ $isDatePast || $isPastMonth ? 'text-gray-400 dark:text-gray-500' : 'text-gray-800 dark:text-gray-200' }}">
                                                    {{ $day }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                @if($hasSlots && !$isDatePast && !$isPastMonth)
                                                    @if($hasDateSpecific)
                                                        <svg class="w-4 h-4 {{ $isDatePast || $isPastMonth ? 'text-gray-400 dark:text-gray-500' : 'text-blue-500 dark:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 {{ $isDatePast || $isPastMonth ? 'text-gray-400 dark:text-gray-500' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        @if(!$isDatePast && !$isPastMonth)
                                            <div 
                                                class="absolute left-0 top-6 w-50 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-2 space-y-1 z-50"
                                                x-show="showDropdown && $wire.selectedDate === '{{ $currentYear }}-{{ $currentMonth }}-{{ $day }}'"
                                                @click.stop>
                                                <button wire:click="editDateSpecificHours('{{ sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day) }}')"
                                                    wire:loading.remove
                                                    wire:target="editDateSpecificHours"
                                                    @click="showDropdown = false"
                                                    class="flex items-center w-full px-2 py-1.5 text-sm text-gray-700 hover:bg-blue-100 rounded dark:text-gray-200 dark:hover:bg-blue-900">
                                                    <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>  
                                                    {{ __('time_slots.edit_date_specific_hours') }}
                                                </button>
                                                @php
                                                    $hasDateSpecificSlots = isset($isDateSpecific[$day]) && $isDateSpecific[$day];
                                                @endphp
                                                @if($hasDateSpecificSlots)
                                                    <button 
                                                        wire:click="confirmResetToWeeklyHours('{{ $currentYear }}-{{ $currentMonth }}-{{ $day }}')"
                                                        wire:loading.remove
                                                        wire:target="confirmResetToWeeklyHours"
                                                        @click="showDropdown = false"
                                                        class="flex items-center w-full px-2 py-1.5 text-sm text-gray-700 hover:bg-blue-100 rounded dark:text-gray-200 dark:hover:bg-blue-900">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        {{ __('time_slots.reset_to_weekly_hours') }}
                                                    </button>
                                                @else
                                                    <button 
                                                        wire:click="editWeeklyDay({{ Carbon\Carbon::create($currentYear, $currentMonth, $day)->dayOfWeek }})"
                                                        wire:loading.remove
                                                        wire:target="editWeeklyDay"
                                                        @click="showDropdown = false"
                                                        class="flex items-center w-full px-2 py-1.5 text-sm text-gray-700 hover:bg-blue-100 rounded dark:text-gray-200 dark:hover:bg-blue-900">
                                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        <span class="text-sm">{{__('time_slots.edit_all_day_of_week', ['day' => Carbon\Carbon::create($currentYear, $currentMonth, $day)->format('l')])}}</span>
                                                    </button>
                                                @endif

                                            </div>
                                        @endif

                                        @php
                                            $currentDate = Carbon\Carbon::create($currentYear, $currentMonth, $day);
                                            $daySlots = collect();
                                            $isPastDate = $currentDate->startOfDay() < Carbon\Carbon::now()->startOfDay();

                                            // Always use status='active' for date-specific slots, regardless of date
                                            $dateSpecificDbSlots = App\Models\AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                                                ->where('type', 'date_specific')
                                                ->where('date', $currentDate->format('Y-m-d'))
                                                ->where('status', 'active')
                                                ->get();
                                            foreach ($dateSpecificDbSlots as $slot) {
                                                $daySlots->push((object)[
                                                    'start_time' => Carbon\Carbon::parse($slot->start_time),
                                                    'end_time' => Carbon\Carbon::parse($slot->end_time),
                                                    'instructor' => $instructors->find($slot->user_id),
                                                    'is_date_specific' => true,
                                                    'status' => $slot->status
                                                ]);
                                            }

                                            if ($dateSpecificDbSlots->isEmpty()) {
                                                if ($isPastDate) {
                                                    // Get all historical weekly slots for this day of week
                                                    $allHistoricalWeeklySlots = App\Models\AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                                                        ->where('type', 'weekly')
                                                        ->where('day_of_week', $currentDate->dayOfWeek)
                                                        ->where('status', 'inactive')
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();
                                                    // Group by created_at
                                                    $grouped = $allHistoricalWeeklySlots->groupBy('created_at');
                                                    $activePatternDate = null;
                                                    foreach ($grouped as $createdAt => $slots) {
                                                        if (Carbon\Carbon::parse($createdAt)->lte($currentDate)) {
                                                            $activePatternDate = $createdAt;
                                                            break;
                                                        }
                                                    }
                                                    // Add all slots from the active pattern for this date
                                                    if ($activePatternDate) {
                                                        foreach ($grouped[$activePatternDate] as $slot) {
                                                            $daySlots->push((object)[
                                                                'start_time' => Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . Carbon\Carbon::parse($slot->start_time)->format('H:i:s')),
                                                                'end_time' => Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . Carbon\Carbon::parse($slot->end_time)->format('H:i:s')),
                                                                'instructor' => $instructors->find($slot->user_id),
                                                                'is_date_specific' => false,
                                                                'status' => $slot->status
                                                            ]);
                                                        }
                                                    }
                                                } else {
                                                    // For current/future dates, show only active weekly slots
                                                    $weeklyDbSlots = App\Models\AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                                                        ->where('type', 'weekly')
                                                        ->where('day_of_week', $currentDate->dayOfWeek)
                                                        ->where('status', 'active')
                                                        ->get();
                                                    foreach ($weeklyDbSlots as $slot) {
                                                        $daySlots->push((object)[
                                                            'start_time' => Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . Carbon\Carbon::parse($slot->start_time)->format('H:i:s')),
                                                            'end_time' => Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . Carbon\Carbon::parse($slot->end_time)->format('H:i:s')),
                                                            'instructor' => $instructors->find($slot->user_id),
                                                            'is_date_specific' => false
                                                        ]);
                                                    }
                                                }
                                            }

                                            // Sort slots by start time
                                            $daySlots = $daySlots->sortBy('start_time');
                                        @endphp
                                        
                                        @foreach($daySlots as $index => $slot)
                                            @if($index < 2)
                                                <div class="text-sm mb-1 p-1 rounded {{ $isDatePast || $isPastMonth ? 'opacity-50' : '' }}
                                                    bg-gray-50 text-gray-700 dark:bg-gray-600 dark:text-gray-3">
                                                    {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                                    @if($slot->instructor)
                                                        <div class="text-sm text-gray-600 dark:text-gray-3">
                                                            {{ $slot->instructor->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(count($daySlots) > 2)
                                            <button 
                                                x-data
                                                x-on:click="$dispatch('openMoreTimeSlots', { 
                                                    date: '{{ $currentDate->format('Y-m-d') }}', 
                                                    slots: {{ json_encode($daySlots->map(function($slot) {
                                                        return [
                                                            'start_time' => $slot->start_time->format('Y-m-d H:i:s'),
                                                            'end_time' => $slot->end_time->format('Y-m-d H:i:s'),
                                                            'is_date_specific' => $slot->is_date_specific,
                                                            'instructor' => $slot->instructor ? [
                                                                'name' => $slot->instructor->name
                                                            ] : null
                                                        ];
                                                    })) }},
                                                    activityClassName: '{{ $activityClass->name }}'
                                                })"
                                                class="text-xs {{$isDatePast || $isPastMonth ? 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400':'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300'}} font-bold mt-1 cursor-pointer"
                                            >
                                                + {{ count($daySlots) - 2 }} more times
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Date-specific Hours Modal -->
        @if($showDateSpecificModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$wire.closeDateSpecificModal()"></div>

                    <!-- Modal panel -->
                    <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full mx-4 sm:mx-auto">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">
                                        @if($editingWeeklyDay !== null)
                                            {{ __('time_slots.edit_all_day_of_week_description', ['day' => __('time_slots.weekdays_long')[$editingWeeklyDay]]) }}
                                        @elseif(count($selectedDates) === 1)
                                            @if(isset($dateSpecificHours[$selectedDates[0]]))
                                                {{ __('time_slots.edit_hours_for_date', ['date' => \Carbon\Carbon::parse($selectedDates[0])->format('M j, Y')]) }}
                                            @else
                                                {{ __('time_slots.add_hours_for_date', ['date' => \Carbon\Carbon::parse($selectedDates[0])->format('M j, Y')]) }}
                                            @endif
                                        @else
                                            {{ __('time_slots.select_date_specific_hours') }}
                                        @endif
                                    </h3>

                                    @if($editingWeeklyDay !== null)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            {{ __('time_slots.select_time_slots_and_instructors', ['day' => __('time_slots.weekdays_long')[$editingWeeklyDay]]) }}
                                        </p>
                                    @endif

                                    <!-- Date Selection Calendar - Only show when not editing weekly day -->
                                    @if($editingWeeklyDay === null)
                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <button wire:click="previousModalMonth" class="text-gray-600 hover:text-gray-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                    </svg>
                                                </button>
                                                <span class="text-lg font-medium">
                                                    {{ Carbon\Carbon::create($modalYear, $modalMonth, 1)->format('F Y') }}
                                                </span>
                                                <button wire:click="nextModalMonth" class="text-gray-600 hover:text-gray-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-7 gap-1 overflow-x-auto">
                                                @foreach(__('time_slots.weekdays_short_initials') as $day)
                                                    <div class="text-center font-medium text-sm py-2 min-w-[40px]">{{ $day }}</div>
                                                @endforeach

                                                @foreach($modalCalendarDays as $day)
                                                    @if($day)
                                                        @php
                                                            $currentModalDate = Carbon\Carbon::create($modalYear, $modalMonth, $day);
                                                            $isDatePast = $currentModalDate->startOfDay() < Carbon\Carbon::now()->startOfDay();
                                                            $dateString = $currentModalDate->format('Y-m-d');
                                                            $isSelected = in_array($dateString, $selectedDates);
                                                        @endphp
                                                        <div class="text-center p-2 border cursor-pointer min-w-[40px]
                                                            {{ $isDatePast ? 'opacity-50 cursor-not-allowed hover:bg-transparent' : 'hover:bg-gray-100' }}
                                                            {{ $isSelected ? 'bg-blue-200' : '' }}
                                                            {{ $dateString === $modalSelectedDate ? 'ring-2 ring-blue-500' : '' }}"
                                                            @if(!$isDatePast)
                                                                wire:click="toggleSelectedDate('{{ $dateString }}')"
                                                            @endif>
                                                            {{ $day }}
                                                        </div>
                                                    @else
                                                        <div class="p-2 min-w-[40px]"></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Time Slots for Selected Dates -->
                                    @if(count($selectedDates) > 0 || $editingWeeklyDay !== null)
                                        <div class="space-y-4">
                                            <h4 class="font-medium">
                                                @if($editingWeeklyDay !== null)
                                                    {{ __('time_slots.time_slots_for_day', ['day' => __('time_slots.weekdays_long')[$editingWeeklyDay]]) }}
                                                @else
                                                    {{ __('time_slots.what_hours_are_you_available') }}
                                                @endif
                                            </h4>
                                            @if($editingWeeklyDay !== null)
                                                @foreach($weeklyTimeSlots[$editingWeeklyDay] ?? [] as $index => $slot)
                                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                                                        <!-- Start Time -->
                                                        <div class="relative w-full sm:w-26">
                                                            <input type="time" 
                                                                wire:model="weeklyTimeSlots.{{ $editingWeeklyDay }}.{{ $index }}.start"
                                                                wire:change="updateEndTime({{ $editingWeeklyDay }}, {{ $index }})"
                                                                class="w-full rounded-md border {{ $errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.start') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-white dark:bg-gray-800 pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200">
                                                            <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                                <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                    <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        
                                                        <span class="text-gray-500 text-sm font-medium hidden sm:block">-</span>
                                                        
                                                        <!-- End Time -->
                                                        <div class="relative w-full sm:w-26">
                                                            <input type="time" 
                                                                wire:model="weeklyTimeSlots.{{ $editingWeeklyDay }}.{{ $index }}.end"
                                                                readonly
                                                                disabled
                                                                class="w-full rounded-md border {{ $errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.end') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200 dark:bg-gray-700">
                                                            <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                                <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                    <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- Instructor Select -->
                                                        <div class="w-full sm:w-45 relative">
                                                            <x-dropdown align="left" width="50">
                                                                <x-slot name="trigger">
                                                                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border {{ $errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.instructor_id') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                        @if(isset($weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id']) && $weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id'])
                                                                            {{ $instructors->find($weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id'])->name }}
                                                                        @else
                                                                            {{ __('time_slots.select_instructor') }}
                                                                        @endif
                                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </button>
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    <div class="py-1 dark:border dark:border-gray-700">
                                                                        <x-dropdown-link wire:click="$set('weeklyTimeSlots.{{ $editingWeeklyDay }}.{{ $index }}.instructor_id', '')" 
                                                                            :selected="!isset($weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id']) || $weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id'] === ''"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900">
                                                                            {{ __('time_slots.select_instructor') }}
                                                                        </x-dropdown-link>
                                                                        @foreach($instructors as $instructor)
                                                                        <x-dropdown-link wire:click="$set('weeklyTimeSlots.{{ $editingWeeklyDay }}.{{ $index }}.instructor_id', '{{ $instructor->id }}')" 
                                                                            :selected="isset($weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id']) && $weeklyTimeSlots[$editingWeeklyDay][$index]['instructor_id'] == $instructor->id"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900">
                                                                            {{ $instructor->name }}
                                                                        </x-dropdown-link>
                                                                        @endforeach
                                                                    </div>
                                                                </x-slot>
                                                            </x-dropdown>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button wire:click="removeTimeSlot({{ $editingWeeklyDay }}, {{ $index }})"
                                                            class="text-red-400 hover:text-red-600 p-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Error messages for the entire row -->
                                                    <div class="ml-6">
                                                        @if ($errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.start') ||
                                                            $errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.end') ||
                                                            $errors->has('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.instructor_id'))
                                                            <p class="mt-1 text-xs text-red-600">
                                                                {{ $errors->first('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.start') }}
                                                                {{ $errors->first('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.end') }} 
                                                                {{ $errors->first('weeklyTimeSlots.'.$editingWeeklyDay.'.'.$index.'.instructor_id') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <!-- Add Time Slot Button -->
                                                <button wire:click="addTimeSlot({{ $editingWeeklyDay }})" 
                                                    class="mt-2 text-blue-600 hover:text-blue-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    {{ __('time_slots.add_time_slot') }}
                                                </button>
                                            @else
                                                @foreach($dateSpecificTimeSlots as $index => $slot)
                                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                                                        <!-- Start Time -->
                                                        <div class="relative w-full sm:w-26">
                                                            <input type="time" 
                                                                wire:model="dateSpecificTimeSlots.{{ $index }}.start"
                                                                wire:change="updateDateSpecificEndTime({{ $index }})"
                                                                class="w-full rounded-md border {{ $errors->has('dateSpecificTimeSlots.'.$index.'.start') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-white dark:bg-gray-800 pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200">
                                                            <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                                <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                    <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        
                                                        <span class="text-gray-500 text-sm font-medium hidden sm:block">-</span>
                                                        
                                                        <!-- End Time -->
                                                        <div class="relative w-full sm:w-26">
                                                            <input type="time" 
                                                                wire:model="dateSpecificTimeSlots.{{ $index }}.end"
                                                                readonly
                                                                disabled
                                                                class="w-full rounded-md border {{ $errors->has('dateSpecificTimeSlots.'.$index.'.end') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50 pl-2 pr-0 py-2 text-gray-800 dark:text-gray-200 dark:bg-gray-700">
                                                            <div class="absolute inset-y-0 right-0 mr-2 flex items-center pr-0 pointer-events-none">
                                                                <svg class="h-3 w-3 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                    <path d="M12 6v6l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- Instructor Select -->
                                                        <div class="w-full sm:w-45 relative">
                                                            <x-dropdown align="left" width="50">
                                                                <x-slot name="trigger">
                                                                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border {{ $errors->has('dateSpecificTimeSlots.'.$index.'.instructor_id') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }} text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                        @if(isset($dateSpecificTimeSlots[$index]['instructor_id']) && $dateSpecificTimeSlots[$index]['instructor_id'])
                                                                            {{ $instructors->find($dateSpecificTimeSlots[$index]['instructor_id'])->name }}
                                                                        @else
                                                                            {{ __('time_slots.select_instructor') }}
                                                                        @endif
                                                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </button>
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    <div class="py-1 dark:border dark:border-gray-700">
                                                                        <x-dropdown-link wire:click="$set('dateSpecificTimeSlots.{{ $index }}.instructor_id', '')" 
                                                                            :selected="!isset($dateSpecificTimeSlots[$index]['instructor_id']) || $dateSpecificTimeSlots[$index]['instructor_id'] === ''"
                                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900">
                                                                            {{ __('time_slots.select_instructor') }}
                                                                        </x-dropdown-link>
                                                                        @foreach($instructors as $instructor)
                                                                            <x-dropdown-link wire:click="$set('dateSpecificTimeSlots.{{ $index }}.instructor_id', '{{ $instructor->id }}')" 
                                                                                :selected="isset($dateSpecificTimeSlots[$index]['instructor_id']) && $dateSpecificTimeSlots[$index]['instructor_id'] == $instructor->id"
                                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900">
                                                                                {{ $instructor->name }}
                                                                            </x-dropdown-link>
                                                                        @endforeach
                                                                    </div>
                                                                </x-slot>
                                                            </x-dropdown>
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button wire:click="removeDateSpecificTimeSlot({{ $index }})"
                                                            class="text-red-400 hover:text-red-600 p-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Error messages for the entire row -->
                                                    <div class="ml-6">
                                                        @if ($errors->has('dateSpecificTimeSlots.'.$index.'.start') ||
                                                            $errors->has('dateSpecificTimeSlots.'.$index.'.end') ||
                                                            $errors->has('dateSpecificTimeSlots.'.$index.'.instructor_id'))
                                                            <p class="mt-1 text-xs text-red-600">
                                                                {{ $errors->first('dateSpecificTimeSlots.'.$index.'.start') }}
                                                                {{ $errors->first('dateSpecificTimeSlots.'.$index.'.end') }} 
                                                                {{ $errors->first('dateSpecificTimeSlots.'.$index.'.instructor_id') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <!-- Add Time Slot Button -->
                                                <button wire:click="addDateSpecificTimeSlot" 
                                                    class="mt-2 text-blue-600 hover:text-blue-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    {{ __('time_slots.add_time_slot') }}
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="{{ $editingWeeklyDay !== null ? 'saveWeeklyDayHours' : 'saveDateSpecificHours' }}" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 {{ $hasDateSpecificChanges ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }} text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                                {{ !$hasDateSpecificChanges ? 'disabled' : '' }}>
                                {{ __('time_slots.apply') }}
                            </button>
                            <button wire:click="closeDateSpecificModal" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('time_slots.cancel') }} 
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Copy Times Modal -->
        @if($showCopyTimesModal)
            <div class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="copyTimesModal">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">
                            {{ __('time_slots.copy_times') }}
                        </h3>
                        <button wire:click="closeCopyTimesModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4 dark:text-white">
                        {{ __('time_slots.copy_times_from', ['day' => __('time_slots.weekdays_short')[$copyFromDay]]) }}
                    </p>
                    
                    <div class="space-y-2">
                        @foreach(__('time_slots.weekdays_short') as $index => $day)
                            @if($copyToDays[$index] !== null)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                        wire:model="copyToDays.{{ $index }}"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="text-sm">{{ $day }}</span>
                                </label>
                            @else
                                <div class="flex items-center space-x-2 opacity-50">
                                    <input type="checkbox" disabled class="rounded border-gray-300 text-gray-400">
                                    <span class="text-sm">{{ $day }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="closeCopyTimesModal" 
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">
                            {{ __('time_slots.cancel') }}
                        </button>
                        <button wire:click="copyTimes" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            {{ __('time_slots.copy_times') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div> 

    @livewire('activity-class.show-more-time-slots') 
</div>