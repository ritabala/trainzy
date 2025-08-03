<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-100 dark:border-gray-700">
    <div class="p-4 sm:p-8">

        <!-- Profile Photo Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ __('members.profile_photo') }}</h3>
                    <div class="ml-2 relative group">
                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 hidden group-hover:block w-64 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 p-3">
                            <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>{{__('members.photo_format1')}}</li>
                                    <li>{{__('members.photo_format2')}}</li>
                                    <li>{{__('members.photo_format3')}}</li>
                                </ul>
                            </div>
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-white dark:bg-gray-700 border-r border-b border-gray-200 dark:border-gray-600"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="relative">
                    @if ($profile_photo)
                        <img src="{{ $profile_photo->temporaryUrl() }}" class="h-32 w-32 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                    @elseif (user()->profile_photo_path)
                        <img src="{{ asset('storage/' . user()->profile_photo_path) }}" class="h-32 w-32 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                            <svg class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="mt-2 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <label for="profile-photo" class="bg-white dark:bg-gray-700 rounded-md px-3 py-1.5 cursor-pointer border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 flex items-center space-x-1">
                            <svg class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ __('common.upload') }}</span>
                        </label>
                        <button type="button" 
                            wire:click="$set('profile_photo', null)" 
                            class="bg-red-500 text-white rounded-md px-3 py-1.5 hover:bg-red-600 flex items-center space-x-1 {{ (!$profile_photo && !user()->profile_photo_path) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ (!$profile_photo && !user()->profile_photo_path) ? 'disabled' : '' }}>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="text-xs">{{ __('common.delete')}}</span>
                        </button>
                    </div>
                    <input type="file" 
                        id="profile-photo" 
                        wire:model="profile_photo" 
                        class="hidden" 
                        accept="image/jpeg,image/png,image/gif,image/jpg"
                        max="1048576"
                    >
                    @error('profile_photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ __('members.basic_info') }}</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.full_name') }}</label>
                    <input type="text" wire:model="name" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.email') }}</label>
                    <input type="email" wire:model="email" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" >
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.phone') }}</label>
                    <input type="tel" wire:model="phone_number" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" >
                    @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.date_of_birth')}}</label>
                    <input type="date" wire:model="date_of_birth" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('date_of_birth') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.gender')}}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$gender">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">{{ $gender ? __('members.gender_values.' . $gender) : __('members.select_gender') }}</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1">
                                <x-dropdown-link :selected="$gender === ''" wire:click="$set('gender', '')">
                                    {{ __('members.select_gender') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$gender === 'male'" wire:click="$set('gender', 'male')">
                                    {{ __('members.gender_values.male') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$gender === 'female'" wire:click="$set('gender', 'female')">
                                    {{ __('members.gender_values.female') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$gender === 'other'" wire:click="$set('gender', 'other')">
                                    {{ __('members.gender_values.other') }}
                                </x-dropdown-link>
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('gender') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{__('members.address')}}</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.address')}}</label>
                    <textarea wire:model="address" rows="3" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter complete address"></textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.city')}}</label>
                    <input type="text" wire:model="city" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter city">
                    @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.state')}}</label>
                    <input type="text" wire:model="state" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter state">
                    @error('state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{__('members.emergency_contact')}}</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.emergency_contact_name')}}</label>
                    <input type="text" wire:model="emergency_contact_name" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter emergency contact name">
                    @error('emergency_contact_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.emergency_contact_phone')}}</label>
                    <input type="tel" wire:model="emergency_contact_phone" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter emergency contact phone">
                    @error('emergency_contact_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button"
                wire:click="save"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                {{ __('common.save') }}
            </button>
        </div>
    </div>
</div> 