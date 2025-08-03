<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-100 dark:border-gray-700">
    <div class="p-8">
        @if (session()->has('error'))
            <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

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
                        <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 hidden group-hover:block w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-3">
                            <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>{{__('members.photo_format1')}}</li>
                                    <li>{{__('members.photo_format2')}}</li>
                                    <li>{{__('members.photo_format3')}}</li>
                                </ul>
                            </div>
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-white dark:bg-gray-800 border-r border-b border-gray-200 dark:border-gray-700"></div>
                        </div>
                    </div>
                </div>
                @if($action === 'edit')
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                            {{ __('staff.staff_id') }} #{{ $staff->id ?? 'N/A' }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="flex-shrink-0">
                    <div class="relative">
                        @if ($profile_photo)
                            <img src="{{ $profile_photo->temporaryUrl() }}" class="h-32 w-32 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                        @elseif ($action === 'edit' && $staff->profile_photo_path)
                            <img src="{{ asset('storage/' . $staff->profile_photo_path) }}" class="h-32 w-32 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                        @else
                            <div class="h-32 w-32 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="mt-2 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <label for="profile-photo" class="bg-white dark:bg-gray-800 rounded-md px-3 py-1.5 cursor-pointer border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center space-x-1">
                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-xs text-gray-600 dark:text-gray-400">Upload</span>
                            </label>
                            <button type="button" 
                                wire:click="{{ $profile_photo ?? '$set(\'profile_photo\', null)' }}" 
                                class="bg-red-500 text-white rounded-md px-3 py-1.5 hover:bg-red-600 flex items-center space-x-1 {{ (!$profile_photo && !($action === 'edit' && $staff->profile_photo_path)) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ (!$profile_photo && !($action === 'edit' && $staff->profile_photo_path)) ? 'disabled' : '' }}>
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
                            data-max-width="400"
                            data-max-height="400">
                    </div>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.full_name') }}</label>
                    <input type="text" wire:model="name" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_name') }}">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.email') }}</label>
                    <input type="email" wire:model="email" autocomplete="off" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_email') }}">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('members.phone') }}</label>
                    <input type="tel" wire:model="phone_number" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_phone') }}">
                    @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                @if($action === 'create')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('staff.pwd') }}</label>
                    <input type="password" wire:model="password" autocomplete="new-password" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_password') }}">
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('staff.confirm_pwd') }}</label>
                    <input type="password" wire:model="password_confirmation" autocomplete="new-password" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_confirm_password') }}">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.date_of_birth')}}</label>
                    <input type="date" wire:model="date_of_birth" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                            <div class="py-1 dark:border dark:border-gray-700">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.address')}}</label>
                    <textarea wire:model="address" rows="3" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_address') }}"></textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.city')}}</label>
                    <input type="text" wire:model="city" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_city') }}">
                    @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.state')}}</label>
                    <input type="text" wire:model="state" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_state') }}">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.emergency_contact_name')}}</label>
                    <input type="text" wire:model="emergency_contact_name" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_emergency_contact_name') }}">
                    @error('emergency_contact_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('members.emergency_contact_phone')}}</label>
                    <input type="tel" wire:model="emergency_contact_phone" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_emergency_contact_phone') }}">
                    @error('emergency_contact_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Professional Details -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ __('staff.prof_details') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('staff.staff_type')}}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$staff_type_id">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">{{ $staff_type_id ? $staffTypes->find($staff_type_id)->name : __('staff.select_type') }}</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                <x-dropdown-link :selected="$staff_type_id === ''" wire:click="$set('staff_type_id', '')">
                                    {{ __('staff.select_type') }}
                                </x-dropdown-link>
                                @foreach($staffTypes as $type)
                                    <x-dropdown-link :selected="$staff_type_id == $type->id" wire:click="$set('staff_type_id', '{{ $type->id }}')">
                                        {{ $type->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('staff_type_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('staff.join_date')}}</label>
                    <input type="date" wire:model="date_of_joining" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('date_of_joining') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('staff.blood')}}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$blood_group">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">{{ $blood_group ? $blood_group : __('staff.select_blood') }}</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                <x-dropdown-link :selected="$blood_group === ''" wire:click="$set('blood_group', '')">
                                    {{ __('staff.select_blood') }}
                                </x-dropdown-link>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <x-dropdown-link :selected="$blood_group === $group" wire:click="$set('blood_group', '{{ $group }}')">
                                        {{ $group }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('blood_group') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('staff.specialization')}}</label>
                    <input type="text" wire:model="specialization" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_specialization') }}">
                    @error('specialization') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-3">
                    <div class="flex flex-col items-start justify-start gap-1 mb-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('staff.certifications') }}
                        </label>
                        <span class="text-xs text-gray-500">
                            ({{ __('staff.certifications_hint') }})
                        </span>
                    </div>
                    <textarea wire:model="certifications" rows="3" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_certifications') }}"></textarea>
                    @error('certifications') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{__('staff.med_history')}}</label>
                    <textarea wire:model="medical_history" rows="3" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_medical_history') }}"></textarea>
                    @error('medical_history') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Is Active -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <input id="is_active" type="checkbox" wire:model="is_active" 
                        class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700"
                        value=1
                        @checked($is_active == 1)
                        @disabled($action === 'edit' && auth()->user()->id === $staff->id)>
                    <label for="is_active" class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('staff.is_active') }}</label>
                    @if($action === 'edit' && auth()->user()->id === $staff->id)
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ __('staff.change_own') }})</span>
                    @endif
                </div>
                @error('is_active') <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button" 
                wire:click="cancel"
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-4">
                {{ __('common.cancel') }}
            </button>
            <button type="button"
                wire:click="save"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $action === 'create' ? __('staff.create') : __('staff.update') }}
            </button>
        </div>
    </div>
</div> 