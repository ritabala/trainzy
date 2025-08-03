<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <!-- Staff Selection -->
        <div class="mb-6" x-data="{ open: false }">
            <label for="staff-select" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                {{ __('staff.filter_by_staff') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="staffSearchQuery"
                    x-on:focus="open = true"
                    x-on:click.away="open = false"
                    placeholder="{{ __('common.search') }} {{ __('staff.staff') }}..." 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-800 dark:border-gray-700 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >

                <!-- Loading indicator -->
                <div wire:loading wire:target="staffSearchQuery" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                </div>

                <!-- Staff List -->
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-300 dark:border-gray-500">
                    <div class="max-h-60 overflow-y-auto">
                        @if(empty($filteredStaffList))
                            <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('staff.no_staff_members_found') }}
                            </div>
                        @else
                            <div class="py-1">
                                @foreach($filteredStaffList as $id => $name)
                                    <button type="button" 
                                        wire:click="$set('selectedStaff', '{{ $id }}')"
                                        x-on:click="open = false; $wire.staffSearchQuery = '{{ $name }}'"
                                        class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-gray-100 {{ $selectedStaff === $id ? 'bg-gray-50 font-medium dark:bg-gray-700 dark:text-gray-100' : '' }}">
                                        {{ $name }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($selectedStaff)
            <!-- Calendar Navigation -->
            <div class="flex justify-between items-center mb-4">
                <button 
                    wire:click="previousMonth" 
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-sm hover:bg-gray-300 dark:hover:bg-gray-600"
                >
                    {{ __('staff.previous_month') }}
                </button>
                <h3 class="text-lg font-semibold">
                    {{ Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                </h3>
                <button 
                    wire:click="nextMonth" 
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-sm hover:bg-gray-300 dark:hover:bg-gray-600"
                >
                    {{ __('staff.next_month') }}
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="hidden md:grid md:grid-cols-7 gap-1">
                <!-- Day Headers -->
                @foreach(__('staff.weekdays_short') as $dayName)
                    <div class="text-center font-semibold py-2 text-sm bg-gray-100 dark:bg-gray-700 rounded-t">
                        {{ $dayName }}
                    </div>
                @endforeach

                <!-- Calendar Days -->
                @foreach($calendarDays as $day)
                    <div class="relative border dark:border-gray-700 p-2 min-h-[120px] {{ $day ? '' : 'bg-gray-50 dark:bg-gray-700' }}">
                        @if($day)
                            <div class="font-semibold text-sm mb-2">{{ $day }}</div>
                            
                            @php
                                $currentDate = Carbon\Carbon::create($currentYear, $currentMonth, $day);
                                $dateString = $currentDate->format('Y-m-d');
                                $dayOfWeek = $currentDate->dayOfWeek;

                                // Get slots for this date
                                $daySlots = $timeSlots->get($dateString, collect())->sortBy('start_time');
                                
                                $visibleSlots = $daySlots->take(2);
                                $remainingSlots = $daySlots->skip(2);
                            @endphp

                            @foreach($visibleSlots as $slot)
                                <div class="text-xs mb-1 p-1 rounded {{ $slot->is_disabled ? 'bg-gray-100 dark:bg-gray-700 text-gray-400' : 'bg-blue-50 dark:bg-blue-900' }}">
                                    <div class="flex items-center gap-1">
                                        @if($slot->type === 'weekly')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-500 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-500 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                        <span>
                                            {{ Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - 
                                            {{ Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        </span>
                                    </div>
                                    <div class="{{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-600 dark:text-gray-400' }}">
                                        {{ $slot->activityClass->name }}
                                    </div>
                                </div>
                            @endforeach

                            @if($remainingSlots->count() > 0)
                            <button 
                                x-data
                                x-on:click="$dispatch('openMoreSlots', { 
                                    date: '{{ $dateString }}', 
                                    slots: {{ json_encode($daySlots->map(function($slot) {
                                        return [
                                            'start_time' => $slot->start_time,
                                            'end_time' => $slot->end_time,
                                            'type' => $slot->type,
                                            'is_disabled' => $slot->is_disabled,
                                            'activity_class' => [
                                                'name' => $slot->activityClass->name
                                            ]
                                        ];
                                    })) }}
                                })"
                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-600 mt-1"
                            >   
                                + {{ $remainingSlots->count() }} {{ __('staff.more_items') }}
                            </button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Mobile List View -->
            <div class="md:hidden space-y-4">
                @foreach($calendarDays as $day)
                    @if($day)
                        @php
                            $currentDate = Carbon\Carbon::create($currentYear, $currentMonth, $day);
                            $dateString = $currentDate->format('Y-m-d');
                            $daySlots = $timeSlots->get($dateString, collect())->sortBy('start_time');
                        @endphp
                        
                        @if($daySlots->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                                <div class="font-semibold text-lg mb-3">
                                    {{ $currentDate->format('l, F j, Y') }}
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($daySlots as $slot)
                                        <div class="p-3 rounded-lg {{ $slot->is_disabled ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900' }}">
                                            <div class="flex items-center gap-2 mb-1">
                                                @if($slot->type === 'weekly')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-500 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-500 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                                <span class="text-sm font-medium">
                                                    {{ Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - 
                                                    {{ Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                </span>
                                            </div>
                                            <div class="text-sm {{ $slot->is_disabled ? 'text-gray-400 dark:text-gray-500' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $slot->activityClass->name }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center min-h-[400px] bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    {{ __('staff.no_staff_selected') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    {{ __('staff.select_staff_to_view_schedule') }}
                </p>
            </div>
        @endif
    </div>

    <!-- 👇 Include the Livewire Modal Component -->
    @livewire('staff-schedule.show-more-slots')
</div>