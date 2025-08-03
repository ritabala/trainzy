<div class="bg-white rounded-lg border border-gray-100 dark:border-gray-700 dark:bg-gray-800">
    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
            {{ session('error') }}
        </div>
    @endif
    <form wire:submit="save">
        <div class="p-6">
            <div class="space-y-6">
                <!-- Recipient Type -->
                <div>
                    <label for="recipient_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('emails.messages.recipient_type_title') }}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$message['recipient_type']">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">
                                    {{ $message['recipient_type'] ? __('emails.messages.recipient_type.' . $message['recipient_type']) : __('emails.messages.recipient_type.select') }}
                                </span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                <x-dropdown-link :selected="!$message['recipient_type']" wire:click="$set('message.recipient_type', '')">
                                    {{ __('emails.messages.recipient_type.select') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$message['recipient_type'] === 'members'" wire:click="$set('message.recipient_type', 'members')">
                                    {{ __('emails.messages.recipient_type.members') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$message['recipient_type'] === 'staff'" wire:click="$set('message.recipient_type', 'staff')">
                                    {{ __('emails.messages.recipient_type.staff') }}
                                </x-dropdown-link>
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('message.recipient_type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Activity Class Filter -->
                @if($message['recipient_type'] === 'members')
                <div>
                    <label for="activity_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('activity.activity_class') }}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$selectedActivityClass">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">
                                    {{ $selectedActivityClass ? $activityClasses->firstWhere('id', $selectedActivityClass)->name : __('common.select') }}
                                </span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                <x-dropdown-link :selected="!$selectedActivityClass" wire:click="$set('selectedActivityClass', '')">
                                    {{ __('common.select') }}
                                </x-dropdown-link>
                                @foreach($activityClasses as $class)
                                    <x-dropdown-link :selected="$selectedActivityClass == $class->id" wire:click="$set('selectedActivityClass', '{{ $class->id }}')">
                                        {{ $class->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('message.activity_class_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                @endif

                <!-- User Selection (for selected_* types) -->
                @if($message['recipient_type'] === 'members' || $message['recipient_type'] === 'staff')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('emails.messages.select_recipients') }}</label>
                    
                    <!-- Selected Users Chips -->
                    @if(count($selectedUsers) > 0)
                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach($selectedUsersData as $user)
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    <span>{{ $user->name }}</span>
                                    <button type="button" wire:click="removeSelectedUser({{ $user->id }})" class="ml-2 text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-100">
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
                                wire:model.live="userSearchQuery" 
                                @focus="open = true"
                                placeholder="{{ __('emails.messages.search') }}"
                                class="w-full text-sm px-3 py-2.5 rounded-md placeholder:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Dropdown Results -->
                        <div x-show="open" 
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-300 dark:border-gray-600 max-h-60 overflow-y-auto">
                            @if(count($filteredUsers) > 0)
                                @foreach($filteredUsers as $user)
                                    <div class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                        wire:click="toggleUserSelection({{ $user->id }})">
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                wire:model.live="selectedUsers" 
                                                value="{{ $user->id }}"
                                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $user->name }} ({{ $user->email }})
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('emails.messages.no_results') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('emails.messages.subject') }}</label>
                    <input type="text" wire:model="message.subject" id="subject" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm ffocus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('message.subject') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('emails.messages.content') }}</label>
                    <textarea wire:model="message.body" id="body" rows="6" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                    @error('message.body') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="p-6 flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
            <button type="button" wire:click="cancel"
                class="w-full sm:w-auto px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('common.cancel') }}
            </button>
            <button type="submit"
                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                {{ $editMode ? __('common.update') : __('common.send') }}
            </button>
        </div>  
    </form>
</div> 