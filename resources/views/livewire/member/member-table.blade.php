
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
                    <div class="relative w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder={{ __('membership.search_members') }} 
                            class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-400 dark:border-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
                        >
                    </div>

                    <!-- Membership Name -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="48" :selectedValue="$membershipId">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-48 inline-flex justify-between items-center px-3 py-2 border dark:border-gray-700 dark:bg-gray-800 dark:text-white border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
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
                                    <x-dropdown-link :selected="!$membershipId" wire:click="$set('membershipId', '')">
                                        {{ __('membership.all_memberships') }}
                                    </x-dropdown-link>
                                    @foreach($memberships as $membership)
                                        <x-dropdown-link :selected="$membershipId == $membership->id" wire:click="$set('membershipId', '{{ $membership->id }}')">
                                            {{ $membership->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Membership Status -->
                    <div class="w-full sm:w-auto">
                        <x-dropdown align="left" width="48" :selectedValue="$membershipStatus">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-48 inline-flex justify-between items-center px-3 py-2 border dark:border-gray-700 dark:bg-gray-800 dark:text-white border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
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
                                    <x-dropdown-link :selected="!$membershipStatus" wire:click="$set('membershipStatus', '')">
                                        {{ __('common.all_status') }}
                                    </x-dropdown-link>
                                    @foreach($membershipStatuses as $statusKey => $statusLabel)
                                        <x-dropdown-link :selected="$membershipStatus === $statusKey" wire:click="$set('membershipStatus', '{{ $statusKey }}')" >
                                            {{ $statusLabel }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Frequency -->
                    <div class="w-full sm:w-auto" x-show="$wire.showMoreFilters" x-transition>
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-48 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <span>
                                        {{ $frequencyId ? $frequencies->find($frequencyId)->name : __('membership.all_frequencies') }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700   ">
                                    <x-dropdown-link :selected="!$frequencyId" wire:click="$set('frequencyId', '')">
                                        {{ __('membership.all_frequencies') }}
                                    </x-dropdown-link>
                                    @foreach($frequencies as $frequency)
                                        <x-dropdown-link :selected="$frequencyId == $frequency->id" wire:click="$set('frequencyId', '{{ $frequency->id }}')" >
                                            {{ $frequency->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    <!-- Date Filter Type -->
                    <div class="w-full sm:w-auto" x-show="$wire.showMoreFilters" x-transition>
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-48 inline-flex justify-between items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <span>
                                        {{ $dateFilterType ? ucfirst($dateFilterType) : __('members.select_filter_by')}}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700">
                                    <x-dropdown-link :selected="!$dateFilterType" wire:click="$set('dateFilterType', '')">
                                        {{ __('members.select_filter_by') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :selected="$dateFilterType === 'startDate'" wire:click="$set('dateFilterType', 'startDate')">
                                        {{ __('members.filter_by_start_date') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :selected="$dateFilterType === 'expiryDate'" wire:click="$set('dateFilterType', 'expiryDate')">
                                        {{ __('members.filter_by_expiry_date') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :selected="$dateFilterType === 'createdAt'" wire:click="$set('dateFilterType', 'createdAt')">
                                        {{ __('members.filter_by_created_at') }}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Date Range Picker -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto" x-show="$wire.showMoreFilters" x-transition>
                        <div class="relative w-full sm:w-48 max-md:mt-2 max-xl:mt-2 mt-2">
                            <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{__('membership.start_date')}}</div>
                            <input 
                                type="date"
                                wire:model.live="dateRangeStart" 
                                @if(!$dateFilterType) disabled @endif
                                class="w-full py-1.5 text-sm rounded-md border-gray-30 disabled:dark:bg-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300 @if(!$dateFilterType) bg-gray-100 dark:bg-gray-500 @endif"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-48 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
                            <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{__('membership.expiry_date')}}</div>
                            <input 
                                type="date" 
                                wire:model.live="dateRangeEnd" 
                                min="{{ $dateRangeStart }}"
                                @if(!$dateFilterType) disabled @endif
                                class="w-full py-1.5 text-sm rounded-md border-gray-300 disabled:dark:bg-gray-400 dark:border-gray-700 dark:text-white dark:bg-gray-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300 @if(!$dateFilterType) bg-gray-100 dark:bg-gray-500 @endif"
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

                        <!-- Reset Filters Button -->
                        @if($search || $membershipId || $membershipStatus || $frequencyId || $dateFilterType || $dateRangeStart || $dateRangeEnd)
                            <button 
                                wire:click="resetFilters"
                                class="whitespace-nowrap text-sm sm:w-auto inline-flex items-center justify-center px-2 py-2 border border-gray-300 dark:border-gray-700 shadow-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200"
                            >
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('membership.clear_filters') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section with top margin -->
    <div class="mt-6">
        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('membership.member_info')}}</th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">
                            {{__('membership.current_membership')}}
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">
                            {{__('membership.membership_frequency')}}
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">
                            {{__('membership.membership_status')}}
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">
                            {{__('membership.membership_period')}}
                        </th>
                        <th wire:click="sortBy('created_at')" 
                            class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider cursor-pointer dark:text-gray-400">
                            {{ __('common.created_at') }}
                            @if($sortField === 'created_at')
                                <span class="ml-1">
                                    {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                </span>
                            @endif
                        </th>
                        @if(auth()->user()->getCachedPermissions()->contains('edit_member') || auth()->user()->getCachedPermissions()->contains('delete_member') || auth()->user()->getCachedPermissions()->contains('view_members'))
                            <th class="px-4 py-2 text-left text-sm font-bold text-gray-500 tracking-wider dark:text-gray-400">{{__('common.actions')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                            <td class="px-4 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 sm:h-12 sm:w-12 flex-shrink-0">
                                        @if($member->profile_photo_path && Storage::disk('public')->exists($member->profile_photo_path))
                                            <img src="{{ Storage::url($member->profile_photo_path) }}" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        @elseif($member->gender)
                                            <img src="{{ asset('images/' . $member->gender . '.svg') }}" alt="Profile" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div class="h-8 w-8 sm:h-12 sm:w-12 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xs sm:text-sm font-bold border border-gray-200 dark:border-gray-600">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 ml-3">
                                        <div class="relative group">
                                            <a href="{{ route('members.show', $member->id) }}" 
                                               class="text-gray-600 hover:font-bold hover:underline truncate block dark:text-gray-300">
                                                {{ $member->name }}
                                            </a>
                                            <div class="text-sm text-gray-500 truncate dark:text-gray-300">
                                                {{ $member->email }}
                                            </div>
                                            <span class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-500 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1 dark:text-gray-300">
                                                <div class="text-gray-300 dark:text-gray-300">{{ $member->name }}</div>
                                                <div class="text-gray-300 dark:text-gray-300">{{ $member->email }}</div>
                                                <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45 dark:bg-gray-500"></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 whitespace-nowrap text-sm dark:text-gray-300">
                                <div class="relative group">
                                    <div class="truncate max-w-[200px]" x-data="{ isTruncated: false }" x-init="isTruncated = $el.scrollWidth > $el.clientWidth">
                                        {{ $member->latestMembership?->membership?->name ?? '-' }}
                                    </div>
                                    @if($member->latestMembership?->membership?->name)
                                        <span x-show="isTruncated" class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-gray-500 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                            {{ $member->latestMembership->membership->name }}
                                            <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 dark:bg-gray-500 transform rotate-45"></div>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-6 whitespace-nowrap text-sm dark:text-gray-300">
                                {{ $member->latestMembership?->membershipFrequency?->frequency?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-6 whitespace-nowrap">
                                <span class="px-2 capitalize inline-flex text-xs font-semibold leading-5 rounded-full 
                                    {{ $member->latestMembership?->membership_status ? $member->latestMembership?->membership_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' : 'text-gray-800' }}">
                                    {{ Str::replace('_', ' ', $member->latestMembership?->membership_status) ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-6 whitespace-nowrap text-sm dark:text-gray-300">
                                <div class="flex flex-col">
                                    <div>{{ $member->latestMembership?->membership_start_date?->format('Y-m-d') ?? '' }}</div>
                                    <div>{{ $member->latestMembership?->membership_expiry_date?->format('Y-m-d') ?? '' }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-6 whitespace-nowrap text-sm dark:text-gray-300">
                                {{ $member->created_at->timezone(gym()->timezone)->format('Y-m-d') ?? '' }}
                            </td>
                            @if(auth()->user()->getCachedPermissions()->contains('edit_member') || auth()->user()->getCachedPermissions()->contains('delete_member') || auth()->user()->getCachedPermissions()->contains('view_members'))
                            <td class="px-4 py-6 whitespace-nowrap text-right text-sm font-medium dark:text-gray-300">
                                <div class="flex space-x-3">
                                    @hasCachedPermission('view_members')
                                    <a href="{{ route('members.show', $member->id) }}" 
                                       class="text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-600 relative group"
                                       aria-label="{{ __('members.view_member') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.view') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @endhasCachedPermission
                                    @hasCachedPermission('edit_member')
                                    <a href="{{ route('members.edit', $member->id) }}" 
                                       class="text-gray-600 hover:text-yellow-600 dark:text-gray-300 dark:hover:text-yellow-600 relative group"
                                       aria-label="{{ __('members.edit_member') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.edit') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @endhasCachedPermission
                                    @hasCachedPermission('delete_member')
                                    <button wire:click="handleDeleteMember({{ $member->id }})"
                                        class="text-gray-600 hover:text-red-600 dark:text-gray-300 dark:hover:text-red-600 relative group"
                                        aria-label="{{ __('members.delete_member') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.delete') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                {{ __('members.no_members_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </div>
</div>
