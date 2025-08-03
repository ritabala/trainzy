<div class="bg-white shadow-xl rounded-lg border border-gray-100 dark:border-gray-700 dark:bg-gray-800">
    <div class="p-4 sm:p-6 md:p-8">
        @if (session()->has('error'))
            <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Step Indicator -->
        @if (!$isEdit)        
            <div class="mb-12 dark:text-gray-300">
                <div class="flex items-center">
                    <div class="flex items-center text-blue-600 relative">
                        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 
                            {{ $currentStep == 1 ? 'border-blue-600 bg-blue-50 dark:border-blue-600 dark:bg-gray-800' : 'border-gray-300 bg-gray-50 dark:border-blue-600 dark:bg-gray-800' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                        <div class="absolute top-0 text-center mt-16 w-25 -ml-5 text-xs font-medium uppercase text-blue-600 dark:text-blue-600">{{ __('members.personal_details') }}</div>
                    </div>
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ $currentStep == 2 ? 'border-blue-600' : 'border-gray-300' }}"></div>
                    <div class="flex items-center text-white relative">
                        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 
                            {{ $currentStep == 2 ? 'border-blue-600 bg-blue-50 text-blue-600 dark:border-blue-600 dark:bg-gray-800 dark:text-blue-600' : 'border-gray-300 bg-gray-50 text-gray-300 dark:border-gray-300 dark:bg-gray-800 dark:text-gray-300' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute top-0 text-center -ml-8 mt-16 w-25 text-xs font-medium uppercase {{ $currentStep == 2 ? 'text-blue-600' : 'text-gray-500 dark:text-gray-300' }}">{{ __('members.membership_details') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 1: Personal Details -->
        @if ($currentStep == 1)
        <div class="bg-white rounded-lg {{ $isEdit ? 'mt-0' : 'mt-18' }} dark:bg-gray-800">
            <!-- Profile Photo Section -->
            <div class="mb-8 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center mb-4 sm:mb-0">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-400">{{ __('members.profile_photo') }}</h3>
                        <div class="ml-2 relative group">
                            <svg class="h-4 w-4 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 hidden group-hover:block w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-3 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-300">
                                <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>{{__('members.photo_format1')}}</li>
                                        <li>{{__('members.photo_format2')}}</li>
                                        <li>{{__('members.photo_format3')}}</li>
                                    </ul>
                                </div>
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-white border-r border-b border-gray-200"></div>
                            </div>
                        </div>
                    </div>
                    @if($isEdit)
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ __('members.member_id') }} #{{ $user->id ?? 'N/A' }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="flex-shrink-0">
                        <div class="relative flex gap-6 items-center">
                            @if ($profilePhoto)
                                <img src="{{ $profilePhoto->temporaryUrl() }}" class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border border-gray-200">
                            @elseif ($isEdit && $user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-700 flex items-center justify-center">
                                    <svg class="h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="mt-2 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <label for="profile-photo" class="bg-white rounded-md px-3 py-1.5 cursor-pointer border border-gray-200 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-700 dark:hover:bg-gray-600 flex items-center justify-center space-x-1 h-fit">
                                    <svg class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">{{__('members.upload')}}</span>
                                </label>
                                <button type="button" 
                                    wire:click="{{ $profilePhoto ? '$set(\'profilePhoto\', null)' : 'deleteProfilePhoto' }}" 
                                    class="bg-red-500 text-white dark:text-gray-300 rounded-md px-3 py-1.5 hover:bg-red-600 flex items-center justify-center space-x-1 h-fit {{ (!$profilePhoto && !($isEdit && $user->profile_photo_path)) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ (!$profilePhoto && !($isEdit && $user->profile_photo_path)) ? 'disabled' : '' }}>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="text-xs text-white dark:text-gray-300">{{__('members.delete')}}</span>
                                </button>
                            </div>
                            <input type="file" 
                                id="profile-photo" 
                                wire:model="profilePhoto" 
                                class="hidden" 
                                accept="image/jpeg,image/png,image/gif,image/jpg"
                                max="1048576"
                                data-max-width="400"
                                data-max-height="400">
                        </div>
                        @error('profilePhoto') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Row 1: Basic Information -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-400">{{ __('members.basic_info') }}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.full_name')}}</label>
                        <input type="text" wire:model.live="fullName" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_name') }}">
                        @error('fullName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.email')}}</label>
                        <input type="email" wire:model.live="email" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_email') }}">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.phone')}}</label>
                        <input type="tel" wire:model.live="phoneNumber" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_phone') }}">
                        @error('phoneNumber') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.date_of_birth')}}</label>
                        <div class="relative">
                            <input 
                                type="date"
                                wire:model.live="dateOfBirth" 
                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"                        >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('dateOfBirth') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.gender')}}</label>
                        <x-dropdown align="left" width="48" :selectedValue="$gender">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="block truncate text-gray-500 dark:text-gray-300">
                                        {{ $gender ? __('members.gender_values.' . $gender) : __('members.select_gender') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
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

            <!-- Row 2: Address -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-400">{{__('members.address')}}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{__('members.address')}}</label>
                        <input type="text" wire:model.live="address" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_address') }}">
                        @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.city')}}</label>
                        <input type="text" wire:model.live="city" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_city') }}">
                        @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.state')}}</label>
                        <input type="text" wire:model.live="state" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_state') }}">
                        @error('state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Row 3: Emergency Contact -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-400">{{__('members.emergency_contact')}}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.emergency_contact_name')}}</label>
                        <input type="text" wire:model.live="emergencyContactName" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_emergency_contact_name') }}">
                        @error('emergencyContactName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.emergency_contact_phone')}}</label>
                        <input type="tel" wire:model.live="emergencyContactPhone" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ __('staff.placeholder_emergency_contact_phone') }}">
                        @error('emergencyContactPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-between border-t dark:border-gray-700 pt-6 space-y-4 sm:space-y-0">
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" wire:click="cancel" 
                        class="px-4 py-2 border border-gray-300 text-sm text-gray-700 dark:text-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{__('common.cancel')}}
                    </button>
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" wire:click="savePersonalDetails" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white">
                        {{ $isEdit ? __('members.save_changes') : __('members.save_exit') }}
                    </button>
                    @if (!$isEdit)
                        <button type="button" wire:click="continue" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white">
                            {{__('members.assign_membership')}}
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Membership Details -->
        @if ($currentStep == 2)
        <div class="bg-white rounded-lg mt-18 dark:bg-gray-800">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.membership_name')}}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$selectedMembershipId">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="block truncate">{{ $selectedMembershipId ? $memberships->find($selectedMembershipId)->name : __('members.select_membership') }}</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                @foreach($memberships as $membership)
                                    <x-dropdown-link :selected="$selectedMembershipId == $membership->id" wire:click="$set('selectedMembershipId', {{ $membership->id }})">
                                        {{ $membership->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('selectedMembershipId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.membership_frequency')}}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$selectedFrequencyId">
                        <x-slot name="trigger">
                            <button type="button" 
                                class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50  focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 {{ empty($availableFrequencies) ? 'bg-gray-50 cursor-not-allowed dark:bg-gray-500' : '' }}"
                                {{ empty($availableFrequencies) ? 'disabled' : '' }}>
                                <span class="block truncate">
                                    @if($selectedFrequencyId)
                                        {{ collect($availableFrequencies)->firstWhere('id', $selectedFrequencyId)['name'] ?? __('members.select_frequency') }}
                                    @else
                                    {{__('members.select_frequency')}}
                                    @endif
                                </span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700">
                                @foreach($availableFrequencies as $frequency)
                                    <x-dropdown-link :selected="$selectedFrequencyId == $frequency['id']" wire:click="$set('selectedFrequencyId', {{ $frequency['id'] }})">
                                        {{ $frequency['name'] }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('selectedFrequencyId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                @if($selectedMembershipId)
                    <div class="col-span-1 lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400 dark:bg-gray-800">{{__('members.membership_services')}}</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 dark:border-gray-700 dark:bg-gray-800">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($memberships as $membership)
                                    @if($membership->id == $selectedMembershipId)
                                        @foreach($membership->membershipServices as $membershipService)
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-200">{{ $membershipService->service->name }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-400 dark:bg-gray-800">{{__('members.select_activity_classes')}}</label>
                        <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 p-4">
                                @foreach($memberships as $membership)
                                    @if($membership->id == $selectedMembershipId)
                                        @foreach($membership->membershipActivityClasses as $membershipActivityClass)
                                            <button type="button" 
                                                wire:click="selectActivityClass({{ $membershipActivityClass->id }})" 
                                                class="group flex items-center space-x-2 p-3 rounded-md border dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 transition-colors duration-150 {{ in_array($membershipActivityClass->id, $selectedActivityClasses) ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                                                <div class="flex-shrink-0 h-4 w-4 rounded border-2 flex items-center justify-center transition-colors duration-150 {{ in_array($membershipActivityClass->id, $selectedActivityClasses) ? 'border-blue-500 bg-blue-500' : 'border-gray-300 group-hover:border-blue-300' }}">
                                                    @if(in_array($membershipActivityClass->id, $selectedActivityClasses))
                                                        <svg class="h-2.5 w-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ $membershipActivityClass->activityClass->name }}</span>
                                            </button>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 lg:col-span-2 mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{__('members.select_time_slots')}}</label>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-200">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-green-50 border border-green-200 dark:border-green-300 dark:bg-green-300 mr-1"></span>
                                    <span>{{__('members.selected_day')}}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-blue-100 border border-blue-200 dark:border-blue-300 dark:bg-blue-300 mr-1"></span>
                                    <span>{{__('members.currently_selected_day')}}</span>
                                </div>
                            </div>
                        </div>
                        @error('selectedTimeSlots') <span class="text-red-500 text-xs mb-2 block">{{ $message }}</span> @enderror
                        <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
                            @foreach($memberships as $membership)
                                @if($membership->id == $selectedMembershipId)
                                    @foreach($membership->membershipActivityClasses as $membershipActivityClass)
                                        @if(in_array($membershipActivityClass->id, $selectedActivityClasses))
                                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $membershipActivityClass->activityClass->name }}</h4>
                                                    <span class="text-xs text-gray-500 dark:text-gray-200">{{__('members.select_day_and_time')}}</span>
                                                </div>
                                                
                                                <!-- Day of Week Selection -->
                                                <div class="mb-6">
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($dayNames as $dayNumber => $dayName)
                                                            @php
                                                                $hasSelectedTimeSlots = false;
                                                                foreach($membershipActivityClass->activityClass->availableTimeSlots
                                                                    ->where('status', 'active')
                                                                    ->where('type', 'weekly')
                                                                    ->where('day_of_week', $dayNumber) as $timeSlot) {
                                                                    if(in_array($timeSlot->id, $selectedTimeSlots)) {
                                                                        $hasSelectedTimeSlots = true;
                                                                        break;
                                                                    }
                                                                }
                                                            @endphp
                                                            <button type="button" 
                                                                wire:click="selectDay({{ $dayNumber }}, {{ $membershipActivityClass->activityClass->id }})" 
                                                                class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                                                {{ ($selectedDays[$membershipActivityClass->activityClass->id] ?? null) == $dayNumber ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:border-blue-300 dark:bg-blue-300 dark:text-blue-700' : 
                                                                ($hasSelectedTimeSlots ? 'bg-green-50 text-green-700 border border-green-200 dark:border-green-300 dark:bg-green-300 dark:text-green-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 dark:border-gray-300 dark:bg-gray-300 dark:text-gray-600') }}">
                                                                {{ $dayName }}
                                                                @if($hasSelectedTimeSlots)
                                                                    <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                @endif
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Time Slots for Selected Day -->
                                                <div>
                                                    <h5 class="text-xs font-medium text-gray-500 mb-3 dark:text-gray-200">{{__('members.available_time_slots')}}</h5>
                                                    @php
                                                        $hasTimeSlots = false;
                                                        foreach($membershipActivityClass->activityClass->availableTimeSlots
                                                            ->where('status', 'active')
                                                            ->where('type', 'weekly')
                                                            ->where('day_of_week', $selectedDays[$membershipActivityClass->activityClass->id] ?? 0) as $timeSlot) {
                                                            $hasTimeSlots = true;
                                                            break;
                                                        }
                                                    @endphp
                                                    @if($hasTimeSlots)
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                                            @foreach($membershipActivityClass->activityClass->availableTimeSlots
                                                                ->where('status', 'active')
                                                                ->where('type', 'weekly')
                                                                ->where('day_of_week', $selectedDays[$membershipActivityClass->activityClass->id] ?? 0) as $timeSlot)
                                                                <button type="button" 
                                                                    wire:click="selectTimeSlot({{ $timeSlot->id }})" 
                                                                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ in_array($timeSlot->id, $selectedTimeSlots) ? 'bg-blue-100 text-blue-700 border border-blue-200 dark:border-blue-300 dark:bg-blue-300 dark:text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 dark:border-gray-300 dark:bg-gray-300 dark:text-gray-600' }}">
                                                                    {{ $timeSlot->start_time->format('h:i A') }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                                                            <div class="flex items-center text-gray-500 dark:text-gray-200">
                                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="text-sm">{{__('members.no_time_slots')}}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.membership_start_date')}}</label>
                    <div class="relative">
                        <input 
                            type="date"
                            wire:model.live="membershipStartDate" 
                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('membershipStartDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">{{__('members.membership_expiry_date')}}
                        <span class="inline-flex items-center text-xs">
                            <svg class="w-3 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                    </label>
                    <div class="relative">
                        <input 
                            type="date"
                            wire:model.live="membershipExpiryDate" 
                            disabled
                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:cursor-not-allowed"                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('membershipExpiryDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-between border-t dark:border-gray-700 pt-6 space-y-4 sm:space-y-0">
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" wire:click="previous" 
                        class="px-4 py-2 border border-gray-300 text-sm text-gray-700 dark:text-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{__('members.previous')}}
                    </button>
                    <button type="button" wire:click="cancel" 
                        class="px-4 py-2 border border-gray-300 text-sm text-gray-700 dark:text-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{__('common.cancel')}}
                    </button>
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white">
                        {{__('common.save')}}
                    </button>
                    <button type="button" wire:click="generateInvoice" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium dark:bg-green-600 dark:hover:bg-green-700 dark:text-white">
                        {{__('members.generate_invoice')}}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
