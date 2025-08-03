<div>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden dark:bg-gray-800">
        <!-- Member Selection -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ $isEditMode ? '' : __('members.attendance.select_members')  }}</h2>
            
            @if($isEditMode)
                <!-- Read-only member display in edit mode -->
                <div class="flex flex-wrap gap-4 mb-2">
                    @foreach($members as $member)
                        <div class="inline-flex items-center justify-between w-full px-4 py-2">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $member->name }}</span>
                            <span class="px-2 py-1 text-xs font-bold bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">ID: {{ $member->id }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Selected Members Chips -->
                @if(count($selectedMembers) > 0)
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach($members->whereIn('id', $selectedMembers) as $member)
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                <span>{{ $member->name }}</span>
                                <button type="button" wire:click="removeMember({{ $member->id }})" class="ml-2 text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Search Box -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <div class="relative">
                        <input type="text" 
                            wire:model.live="memberSearchQuery" 
                            @focus="open = true"
                            placeholder="{{ __('members.attendance.search_members') }}"
                            class="w-full text-sm px-3 py-2.5 rounded-md placeholder:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <!-- Dropdown Results -->
                    <div x-show="open" 
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-300 dark:border-gray-600 max-h-60 overflow-y-auto">
                        @if(count($members) > 0)
                            @foreach($members as $member)
                                <div class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                    wire:click="toggleMemberSelection({{ $member->id }})">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                            wire:model.live="selectedMembers" 
                                            value="{{ $member->id }}"
                                            @click.stop
                                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $member->name }} ({{ $member->email }})
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('members.attendance.no_members') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @error('selectedMembers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Date and Time Selection -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.attendance.check_in') }}</label>
                    <input type="datetime-local" wire:model="date" id="date" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('members.attendance.notes') }}</h3>
            <textarea wire:model="notes" id="notes" rows="4" placeholder="{{ __('members.attendance.notes_placeholder') }}" 
                class="w-full placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
            @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Action Buttons -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('attendance.members.index') }}" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('common.cancel') }}
                </a>
                <button type="button" wire:click="save" 
                    class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-sm text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $attendance_id ? __('common.update') : __('common.submit') }}
                </button>
            </div>
        </div>
    </div>
</div> 