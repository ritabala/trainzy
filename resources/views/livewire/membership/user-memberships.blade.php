<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="pb-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <!-- Main Filter Container -->
            <div class="w-full">
                <!-- All Filters in One Row -->
                <div class="flex flex-wrap items-end gap-4">
                    <!-- Search -->
                    <div class="relative w-full sm:w-40">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="{{ __('membership.search_members') }}"
                            class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
                        >
                    </div>

                    <!-- Membership Filter -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-40 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <span>
                                        {{ $membershipId ? $memberships->find($membershipId)->name : __('membership.all_memberships') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link wire:click="$set('membershipId', '')" :selected="!$membershipId">
                                        {{ __('membership.all_memberships') }}
                                    </x-dropdown-link>
                                    @foreach($memberships as $membership)
                                        <x-dropdown-link wire:click="$set('membershipId', '{{ $membership->id }}')" :selected="$membershipId == $membership->id">
                                            {{ $membership->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Frequency Filter -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-40 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <span>
                                        {{ $frequencyId ? $frequencies->find($frequencyId)->name : __('membership.all_frequencies') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link wire:click="$set('frequencyId', '')" :selected="!$frequencyId">
                                        {{ __('membership.all_frequencies') }}
                                    </x-dropdown-link>
                                    @foreach($frequencies as $frequency)
                                        <x-dropdown-link wire:click="$set('frequencyId', '{{ $frequency->id }}')" :selected="$frequencyId == $frequency->id">
                                            {{ $frequency->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Membership Status Filter -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-40 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <span>
                                        {{ $membershipStatus ? $membershipStatuses[$membershipStatus] : __('common.all_status') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link wire:click="$set('membershipStatus', '')" :selected="!$membershipStatus">
                                        {{ __('common.all_status') }}
                                    </x-dropdown-link>
                                    @foreach($membershipStatuses as $statusKey => $statusLabel)
                                        <x-dropdown-link wire:click="$set('membershipStatus', '{{ $statusKey }}')" :selected="$membershipStatus === $statusKey">
                                            {{ $statusLabel }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Auto Renewal Filter -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="40">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-40 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"> 
                                    <span>
                                        @if($autoRenewal === '1')
                                            {{ __('membership.yes') }}
                                        @elseif($autoRenewal === '0')
                                            {{ __('membership.no') }}
                                        @else
                                            {{ __('membership.all_auto_renewal') }}
                                        @endif
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                    
                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link wire:click="$set('autoRenewal', '')" :selected="!in_array($autoRenewal, ['1', '0'])">
                                        {{ __('membership.all_auto_renewal') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="$set('autoRenewal', '1')" :selected="$autoRenewal === '1'">
                                        {{ __('membership.yes') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="$set('autoRenewal', '0')" :selected="$autoRenewal === '0'">
                                        {{ __('membership.no') }}
                                    </x-dropdown-link>
                                </div>  
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Date Range Picker -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto mt-2" x-show="$wire.showMoreFilters" x-transition>
                        <div class="relative w-full sm:w-40 max-xl:mt-2">
                            <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{__('membership.start_date')}}</div>
                            <input 
                                type="date"
                                wire:model.live="startDate"
                                class="w-full py-1.5 text-sm rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-40 max-xl:mt-4">
                            <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{__('membership.expiry_date')}}</div>
                            <input 
                                type="date" 
                                wire:model.live="expiryDate"
                                min="{{ $startDate }}"
                                class="w-full py-1.5 text-sm rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4 sm:w-auto">
                        <!-- More/Hide Filters Button -->
                        <button 
                            wire:click="$toggle('showMoreFilters')" 
                            class="whitespace-nowrap text-sm sm:w-auto inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-gray-700 shadow-sm leading-4 font-medium rounded-md text-gray-50 bg-indigo-500 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 focus:outline-none"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ $showMoreFilters ? __('membership.hide_filters') : __('membership.more_filters') }}
                        </button>
                    
                        <!-- Reset Filters -->
                        @if($search || $membershipId || $frequencyId || $startDate || $expiryDate || $membershipStatus)
                            <button 
                                wire:click="resetFilters"
                                class="whitespace-nowrap text-sm sm:w-auto inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-gray-700 shadow-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200"
                            >
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{__('membership.clear_filters')}}
                            </button>
                        @endif
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mt-6">
        <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.member_info')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.current_membership')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.membership_frequency')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.membership_status')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.auto_renewal')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.membership_period')}}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-100 text-sm dark:hover:bg-gray-700 dark:text-gray-300">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div>
                                        <a href="{{ route('members.show', $user->user_id)  }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:underline hover:underline-offset-2">{{ $user->user->name }}</a>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->membership->name }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->membershipFrequency->frequency->name }}</div>
                            </td>
                            @php
                                $status = $user->membership_status;

                                $statusClasses = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'expired' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    'upcoming' => 'bg-gray-100 text-gray-800',
                                    'suspended' => 'bg-gray-100 text-gray-800',
                                ];

                                $class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp

                            <td class="px-4 py-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ __('membership.user_membership_status.' . $status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $autoRenewal = $user->auto_renewal ? __('membership.yes') : __('membership.no');
                                    $autoRenewalClass = $user->auto_renewal ? 'text-green-500' : 'text-red-500';
                                @endphp
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="{{ $autoRenewalClass }}">{{ $autoRenewal }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $user->membership_start_date->format('M d, Y') }} - 
                                    {{ $user->membership_expiry_date->format('M d, Y') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                {{__('membership.no_memberships_found')}}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div> 