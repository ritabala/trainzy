    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <form wire:submit.prevent="{{ $membershipId ? 'confirmMembershipUpdate(' . $membershipId . ')' : 'save' }}">
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('membership.name') }}</label>
                    <input type="text" wire:model="name" id="name" placeholder="{{ __('membership.plc_name') }}"
                        class="mt-1 block w-full rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tagline -->
                <div>
                    <label for="tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('membership.tagline') }}</label>
                    <input type="text" wire:model="tagline" id="tagline" placeholder="{{ __('membership.plc_tagline') }}"
                        class="mt-1 block w-full text-sm rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('tagline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('membership.description') }}</label>
                    <textarea wire:model="description" id="description" rows="4" placeholder="{{ __('membership.plc_description') }}"
                        class="mt-1 text-sm block w-full rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Frequencies & Prices -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('membership.frequency_and_price') }}</label>
                    <div class="space-y-4 mt-1">
                        @foreach($this->frequencies as $index => $freq)
                            <div class="flex items-center space-x-4">
                                <div class="w-1/2">
                                    <x-dropdown align="left" width="48">
                                        <x-slot name="trigger">
                                            <button type="button" 
                                                class="inline-flex border justify-between w-full text-sm px-2.5 py-2.5 items-center dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="block truncate">
                                                    @if($frequencies[$index]['id'])
                                                        {{ $this->availableFrequencies->firstWhere('id', $frequencies[$index]['id'])->name }}
                                                    @else
                                                        {{ __('membership.select_frequency') }}
                                                    @endif
                                                </span>
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1 dark:border dark:border-gray-700">
                                                @foreach($this->availableFrequencies as $frequency)
                                                    @php
                                                        $isSelected = $frequencies[$index]['id'] == $frequency->id;
                                                        $isDisabled = in_array($frequency->id, array_column($this->frequencies, 'id')) && $frequencies[$index]['id'] != $frequency->id;
                                                        $classes = $isDisabled ? ' opacity-50 cursor-not-allowed' : '';
                                                    @endphp
                                                    <x-dropdown-link 
                                                        href="#"
                                                        wire:click.prevent="$set('frequencies.{{ $index }}.id', '{{ $frequency->id }}')"
                                                        class="{{ $classes }}"
                                                        :selected="$isSelected"
                                                        :disabled="$isDisabled">
                                                        {{ $frequency->name }}
                                                    </x-dropdown-link>
                                                @endforeach
                                            </div>
                                        </x-slot>
                                    </x-dropdown>
                                    @error("frequencies.{$index}.id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-1/3">
                                    <input type="number" placeholder="{{ __('membership.plc_price') }}" wire:model="frequencies.{{ $index }}.price" step="0.01"
                                        class="block w-full text-sm rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error("frequencies.{$index}.price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                @if(count($this->frequencies) > 1)
                                    <button type="button" wire:click="removeFrequency({{ $index }})"
                                        class="px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">-</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('frequencies') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if(count($availableFrequencies) > count($frequencies))
                        <button type="button" wire:click="addFrequency"
                            class="mt-2 px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700">
                            {{ __('membership.add_frequency') }}
                        </button>
                    @else
                        <button type="button" disabled
                            class="mt-2 px-3 py-1 bg-gray-400 text-white rounded-md cursor-not-allowed">
                            {{ __('membership.add_frequency') }}
                        </button>
                    @endif
                </div>

                <!-- Activity Classes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('membership.activity_class') }}</label>
                    <div class="mt-1">
                        <!-- Selected Activity Classes -->
                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach($this->activityClasses as $selectedId)
                                @php
                                    $activityClass = $this->availableActivityClasses->firstWhere('id', $selectedId);
                                @endphp
                                @if($activityClass)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-gray-300">
                                        {{ $activityClass->name }}
                                        <button type="button" wire:click="removeActivityClass({{ $selectedId }})" class="ml-1.5 text-indigo-600 hover:text-indigo-900 dark:text-gray-300">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            @endforeach
                        </div>

                        <!-- Activity Class Dropdown with Search -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <div @click="open = !open" 
                                class="w-full text-sm rounded-md border border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 shadow-sm p-2 cursor-pointer bg-white flex items-center justify-between">
                                @if(count($this->activityClasses) > 0)
                                    <span class="truncate">
                                        {{ count($this->activityClasses) }} {{ __('membership.selected') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">{{ __('membership.select_activity_classes') }}</span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div x-show="open" x-cloak 
                                class="absolute z-10 w-full bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 rounded-md shadow-lg"
                                style="max-height: 300px; overflow-y: auto;"
                                x-bind:style="{
                                    'top': $el.getBoundingClientRect().bottom + 200 <= window.innerHeight ? '100%' : 'auto',
                                    'bottom': $el.getBoundingClientRect().bottom + 200 > window.innerHeight ? '100%' : 'auto'
                                }">
                                <!-- Search input -->
                                <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="text" 
                                        wire:model.live.debounce.300ms="activityClassSearch"
                                        placeholder="{{ __('activity.search') }}"
                                        class="w-full text-sm border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <!-- Activity Classes List -->
                                <div class="max-h-40 overflow-y-auto">
                                    @if($this->filteredActivityClasses->count() > 0)
                                        <ul class="py-1">
                                            @foreach($this->filteredActivityClasses as $activityClass)
                                                <li wire:click="toggleActivityClass({{ $activityClass->id }})" 
                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                                    <input type="checkbox" 
                                                        class="mr-2 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" 
                                                        @if(in_array($activityClass->id, $this->activityClasses)) checked @endif>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $activityClass->name }}
                                                        @if($activityClass->description)
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">- {{ $activityClass->description }}</span>
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="px-3 py-2 text-gray-500 text-sm dark:text-gray-400">{{ __('activity.no_activity_classes_found') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('activityClasses') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Services -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">{{ __('membership.service') }}</label>
                    <div class="max-h-50 overflow-y-auto pr-1 border border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($this->availableServices as $service)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="services" value="{{ $service->id }}" 
                                        id="service_{{ $service->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="service_{{ $service->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $service->name }}
                                        @if($service->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->description }}</p>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('services') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" id="is_active" 
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">{{ __('membership.active') }}</label>
                    @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $membershipId ? __('membership.update') : __('membership.create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
