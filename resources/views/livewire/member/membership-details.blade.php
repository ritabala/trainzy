<div class="space-y-6">
    <!-- Latest Membership Section -->
    <div class="bg-white border border-gray-100 overflow-hidden sm:rounded-lg dark:bg-gray-800 dark:border-gray-700 ">
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row gap-4 sm:gap-0 justify-between lg:items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200">{{ __('membership.current_membership') }}</h3>
            </div>
            <div class="space-x-3 flex">
                @if($showGenerateInvoiceButton)
                    <button wire:click="showGenerateInvoice" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-700 flex items-center dark:bg-yellow-600 dark:hover:bg-yellow-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt mr-2" viewBox="0 0 16 16">
                            <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                            <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        {{__('members.generate_invoice')}}
                    </button>
                @endif
                @if($showEditButton)
                    <button wire:click="openModal('edit')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 flex items-center dark:bg-blue-600 dark:hover:bg-blue-700">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            {{ __('members.edit_dates') }}
                        </span>
                    </button>
                @endif
                @if($showAssignButton)
                    <button wire:click="openModal('assign')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700 flex items-center dark:bg-green-600 dark:hover:bg-green-700">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            {{  __('members.assign_new_membership') }}
                        </span>
                    </button>
                @endif
                @if($showRenewButton)
                    <button wire:click="confirmRenew" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700 flex items-center dark:bg-green-600 dark:hover:bg-green-700">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            {{ __('members.renew') }}
                        </span> 
                    </button>
                @endif
            </div>
        </div>
        @if($latestMembership)
            @if($showStopAutoRenewalButton || $upcomingRenewedMembership)
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-blue-50 p-4 dark:bg-gray-700">
                        <div class="flex flex-col lg:flex-row justify-between gap-4">
                            <div class="flex items-center">
                                <p class="text-sm text-blue-700 dark:text-blue-300 inline-flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @if($showStopAutoRenewalButton)
                                        {{ __('members.membership_set_for_renewal', ['date' => \Carbon\Carbon::parse($latestMembership->next_renewal_date)->format('M d, Y')]) }}
                                        <span class="font-medium">({{ __('members.auto_renew') }})</span>
                                    @else
                                        {{ __('members.membership_set_for_renewal', ['date' => \Carbon\Carbon::parse($upcomingRenewedMembership->membership_start_date)->format('M d, Y')]) }}
                                        <span class="font-medium">({{ __('members.manual_renew') }})</span>
                                    @endif
                                </p>
                            </div>
                            
                            <button 
                                wire:click="{{ $showStopAutoRenewalButton ? 'confirmStopAutoRenewal' : 'removeUpcomingRenewedMembership' }}"
                                class="ml-4 inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $showStopAutoRenewalButton ? __('members.stop_auto_renew_btn') : __('members.cancel_renew_btn') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-6 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white dark:bg-gray-800 p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 dark:border-gray-700 dark:text-gray-200 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{__('members.basic_info')}}
                        </h4>
                        <div class="space-y-2">
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('common.name') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->membership->name }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('members.freq') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->membershipFrequency->frequency->name }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('members.status') }}:</span>
                                <strong class="px-2 py-1 rounded-full text-xs font-medium {{ strtolower($latestMembership->membership_status) === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($latestMembership->membership_status) }}
                                </strong>
                            </p>
                        </div>
                    </div>

                    <!-- Membership Dates Card -->
                    <div class="bg-white dark:bg-gray-800 p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 dark:border-gray-700 dark:text-gray-200 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{__('members.membership_dates')}}
                        </h4>
                        <div class="space-y-2">
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('membership.start_date') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->membership_start_date->format('M d, Y') }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('membership.expiry_date') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->membership_expiry_date->format('M d, Y') }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('membership.last_renewal') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->last_renewal_date ? $latestMembership->last_renewal_date->format('M d, Y') : 'N/A' }}</strong>
                            </p>
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('membership.next_renewal') }}:</span>
                                <strong class="dark:text-gray-200">{{ $latestMembership->next_renewal_date ? $latestMembership->next_renewal_date->format('M d, Y') : 'N/A' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Renewal Settings Card -->
                    <div class="bg-white dark:bg-gray-800 p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 dark:border-gray-700 dark:text-gray-200 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{__('membership.renewal_settings')}}
                        </h4>
                        <div class="space-y-2">
                            <p class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('membership.auto_renewal') }}:</span>
                                <strong class="px-2 py-1 rounded-full text-xs font-medium {{ $latestMembership->auto_renewal ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $latestMembership->auto_renewal ? __('members.enabled') : __('members.disabled') }}
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
        <div class="px-4 py-5 sm:px-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{__('members.no_active_membership')}}</p>
        </div>
        @endif
    </div>

    <!-- Unified Modal -->
    @if($showModal)
    <x-dialog-modal id="membershipModal" maxWidth="lg" wire:model="showModal" class="z-10">
        <x-slot name="title">
            @if($modalAction === 'edit')
                <span class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>{{__('members.edit_title')}}</span>
            @elseif($modalAction === 'renew')
                <span class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>{{__('members.renew_title')}}</span>
            @else
                <span class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> {{__('members.assign_new_membership')}}</span>
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                <!-- Membership Selection -->
                <div class="bg-gray-50 p-4 rounded-lg transition-all duration-200 hover:shadow-md dark:bg-gray-700">
                    <label for="selectedMembershipId" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        {{ __('members.membership_name') }}
                        @if($modalAction === 'edit' || $modalAction === 'renew')
                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                {{ __('common.locked') }}
                            </span>
                        @endif
                    </label>
                    <x-dropdown align="left" width="w-full">
                        <x-slot name="trigger">
                            <button type="button" 
                                    class="w-full flex justify-between items-center px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                                    @if($modalAction === 'edit' || $modalAction === 'renew') disabled @endif>
                                <span>{{ $selectedMembershipId ? $availableMemberships->firstWhere('id', $selectedMembershipId)?->name : __('members.select_membership') }}</span>
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700 dark:bg-gray-500 dark:text-gray-200">
                                <x-dropdown-link wire:click="$set('selectedMembershipId', '')" :selected="!$selectedMembershipId">
                                    {{ __('members.select_membership') }}
                                </x-dropdown-link>
                                @foreach($availableMemberships as $membership)
                                    <x-dropdown-link wire:click="$set('selectedMembershipId', '{{ $membership->id }}')" :selected="$selectedMembershipId == $membership->id">
                                        {{ $membership->name }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('selectedMembershipId') 
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Frequency Selection -->
                <div class="bg-gray-50 p-4 rounded-lg transition-all duration-200 hover:shadow-md dark:bg-gray-700">
                    <label for="selectedFrequencyId" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        {{ __('members.freq') }}
                        @if($modalAction === 'edit' || $modalAction === 'renew')
                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                {{ __('common.locked') }}
                            </span>
                        @endif
                    </label>
                    <x-dropdown align="left" width="w-full">
                        <x-slot name="trigger">
                            <button type="button" 
                                    class="w-full flex justify-between items-center px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200"
                                    @if($modalAction === 'edit' || $modalAction === 'renew' || empty($availableFrequencies)) disabled @endif>
                                <span>{{ $selectedFrequencyId ? collect($availableFrequencies)->firstWhere('id', $selectedFrequencyId)['name'] : __('members.select_frequency') }}</span>
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border dark:border-gray-700 dark:bg-gray-500 dark:text-gray-200">
                                <x-dropdown-link wire:click="$set('selectedFrequencyId', '')" :selected="!$selectedFrequencyId">
                                    {{ __('members.select_frequency') }}
                                </x-dropdown-link>
                                @foreach($availableFrequencies as $frequency)
                                    <x-dropdown-link wire:click="$set('selectedFrequencyId', '{{ $frequency['id'] }}')" :selected="$selectedFrequencyId == $frequency['id']">
                                        {{ $frequency['name'] }}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                    @error('selectedFrequencyId') 
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Dates Section -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('membership.start_date') }}</label>
                        <input type="date" 
                            wire:model="membershipStartDate" 
                            wire:change="calculateDatesBasedOnFrequency($event.target.value)"
                            class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                        @error('membershipStartDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{__('membership.expiry_date')}}</label>
                        <input type="date" 
                            wire:model="membershipEndDate" 
                            class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 disabled:cursor-not-allowed"
                            {{ $modalAction === 'edit' || $modalAction === 'renew' ? 'disabled' : '' }}>
                        @error('membershipEndDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Auto Renewal Section -->
                @if($modalAction === 'assign' || $showRenewalFields)
                    <div class="bg-blue-50 p-4 rounded-lg space-y-4 transition-all duration-200 hover:shadow-md dark:bg-gray-700 dark:border-gray-500 dark:text-gray-200">
                        <div class="flex items-center justify-between border-b border-blue-100 dark:border-gray-600 pb-4">
                            <label for="autoRenewal" class="text-sm font-medium text-gray-700 dark:text-gray-200  flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ __('membership.auto_renewal') }}
                            </label>
                            <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                <input type="checkbox" 
                                       wire:model.live="autoRenewal" 
                                       id="autoRenewal" 
                                       class="toggle-checkbox absolute block w-6 h-6 rounded-full  bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="autoRenewal" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>

                        @if($autoRenewal)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($modalAction === 'assign')
                                    <!-- For Assign: Only show Next Renewal Date -->
                                    <div class="col-span-2">
                                        <label for="nextRenewalDate" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">{{__('membership.next_renewal_date')}}</label>
                                        <input type="date" 
                                               wire:model.live="nextRenewalDate" 
                                               id="nextRenewalDate" 
                                               class="mt-1 block w-full rounded-md bg-gray-50 disabled:bg-gray-100 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200"
                                               readonly disabled>
                                        @error('nextRenewalDate') 
                                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @else
                                    <!-- For Renew: Show both Last and Next Renewal Dates -->
                                    <div>
                                        <label for="lastRenewalDate" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">
                                            {{ __('membership.last_renewal_date') }}
                                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                {{ __('common.locked') }}
                                            </span>
                                        </label>
                                        <input type="date" 
                                               wire:model.live="lastRenewalDate" 
                                               id="lastRenewalDate" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 dark:text-gray-200 disabled:cursor-not-allowed transition-all duration-200"
                                               disabled>
                                        @error('lastRenewalDate') 
                                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nextRenewalDate" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">{{__('membership.next_renewal_date')}}</label>
                                        <input type="date" 
                                               wire:model.live="nextRenewalDate" 
                                               id="nextRenewalDate" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 dark:bg-gray-700 dark:text-gray-200 disabled:cursor-not-allowed">
                                        @error('nextRenewalDate') 
                                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" 
                    wire:click="save" 
                    class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($modalAction === 'edit')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    @elseif($modalAction === 'renew')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    @endif
                </svg>
                {{ $modalAction === 'edit' ? 'Update' : ($modalAction === 'renew' ? __('members.renew') : __('members.assign')) }}
            </button>
            <button type="button" 
                    wire:click="closeModal" 
                    class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ __('common.cancel') }}
            </button>
        </x-slot>
    </x-dialog-modal>

    <style>
        .toggle-checkbox:checked {
            @apply: right-0 border-blue-500;
            right: 0;
            border-color: #3b82f6;
        }
        .toggle-checkbox:checked + .toggle-label {
            @apply: bg-blue-500;
            background-color: #3b82f6;
        }
    </style>
    @endif

    <!-- Membership History Section -->
    <div class="bg-white border border-gray-100 overflow-hidden sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200">{{__('members.membership_history')}}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('members.membership')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('members.freq')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('membership.start_date')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('membership.expiry_date')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('common.status')}}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-200">{{__('common.created_at')}}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @if($historicalMemberships->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{__('members.no_historical_memberships')}}</td>
                        </tr>
                    @else
                        @foreach($historicalMemberships as $membership)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ $membership->membership->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $membership->membershipFrequency->frequency->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $membership->membership_start_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $membership->membership_expiry_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ strtolower($membership->membership_status) === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($membership->membership_status) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $membership->created_at->timezone(gym()->timezone)->format('M d, Y') }}
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
