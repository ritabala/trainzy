<div class="bg-white rounded-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-4 sm:p-6 md:p-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6 md:mb-8">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-200">{{__('members.activity-title')}}</h3>
                @if($showNoMembershipAssigned)
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 dark:text-gray-400">{{__('members.activity-sub-title1')}}</p>
                @else
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 dark:text-gray-400">{{__('members.activity-sub-title2')}}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($activityClasses as $activityClass)
                <div class="bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 hover:border-blue-200 transition-all duration-200 shadow-sm hover:shadow-md group">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-0">
                            <h4 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200 truncate text-ellipsis">{{ $activityClass->name }} </h4> 
                            
                            <button wire:click="openEditModal({{ $activityClass->id }})" class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full mb-3 sm:mb-4">
                            {{ $activityClass->duration }} {{ __('members.min') }}
                        </span>
                        
                        <div class="space-y-2 sm:space-y-3 mt-2">
                            @foreach($dayNames as $dayNumber => $dayName)
                                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-150 dark:hover:bg-gray-800">
                                    <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-200 ">{{ $dayName }}</span>
                                    <div>
                                        @if($activityClass->availableTimeSlots && $activityClass->availableTimeSlots->where('day_of_week', $dayNumber)->where('is_selected', true)->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($activityClass->availableTimeSlots->where('day_of_week', $dayNumber)->where('is_selected', true) as $slot)
                                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $slot->start_time->format('h:i A') }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs sm:text-sm text-gray-400 dark:text-gray-400">{{__('members.not_scheduled')}}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Edit Modal -->
    @if($editingActivity)
    <x-dialog-modal wire:model="editingActivity">
        <x-slot name="title">
            {{ __('members.scheduled_for') }}{{ $modalActivityClass->name }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-2">
                @foreach($dayNames as $dayNumber => $dayName)
                <div class="border border-gray-100 dark:border-gray-600 rounded-lg p-2">
                    <div class="flex justify-between mb-1">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $dayName }}</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ isset($timeSlotsByDay[$dayNumber]) ? collect($timeSlotsByDay[$dayNumber])->where('is_selected', true)->count() : 0 }} selected
                        </span>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-1">
                        @if(isset($timeSlotsByDay[$dayNumber]))
                            @foreach($timeSlotsByDay[$dayNumber] as $slot)
                                <button wire:click="toggleTimeSlot({{ $slot['id'] }})"
                                        wire:key="slot-{{ $slot['id'] }}"
                                        class="relative px-1 sm:px-1.5 py-0.5 sm:py-1 rounded text-xs font-medium border border-gray-200
                                        {{ $slot['is_selected'] ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700' }}">
                                    {{ \Carbon\Carbon::parse($slot['start_time'])->format('h:i A') }}
                                </button>
                            @endforeach
                        @else
                            <span class="text-xs text-gray-500">No slots</span>
                        @endif
                    </div>
                </div>
            @endforeach
            
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" 
                    wire:click="saveSchedule"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 dark:bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                    wire:loading.attr="disabled">
                {{ __('common.save_changes') }}
            </button>
            <button type="button" 
                    wire:click="closeModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    wire:loading.attr="disabled">
                {{ __('common.cancel') }}
            </button>
        </x-slot>
    </x-dialog-modal>
    @endif
</div>