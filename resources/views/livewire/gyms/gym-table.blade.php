<div>
<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center pb-7 border-b border-gray-200 dark:border-gray-800 gap-4">
        <!-- Search -->
        <div class="w-full sm:w-64 mr-4 sm:mb-0 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="{{ __('gym.search_gym') }}" 
                autocomplete="off"
                class="w-full py-1.5 text-gray-700 dark:text-gray-300 dark:bg-gray-800 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
            >
        </div>

        <!-- Currency Filter -->
        <div class="w-full mr-4 sm:w-64">
            <x-dropdown align="left" width="48" :selectedValue="$currencyId">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
                        <span>
                            {{ $currencyId == '' ? __('gym.select_currency') : $selectedCurrencyName }}
                        </span>
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="py-1 dark:border dark:border-gray-700">
                        <x-dropdown-link :selected="$currencyId == ''" wire:click="$set('currencyId', '')">
                            {{ __('gym.select_currency') }}
                        </x-dropdown-link>
                        @foreach($currencies as $currency)
                            <x-dropdown-link :selected="$currencyId == $currency->id" wire:click="$set('currencyId', '{{ $currency->id }}')">
                                {{ $currency->name }}
                            </x-dropdown-link>
                        @endforeach     
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
        
        <!-- Reset Filters -->
        @if($this->currencyId || $this->search)
        <div class="w-full mr-4 sm:w-48">
            <button 
                wire:click="resetFilters"
                class="inline-flex items-center px-3 py-1 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 border border-red-300 dark:border-red-100 transition-colors duration-200"
            >
                {{ __('common.clear_filters') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="mt-6">
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('gym.gym_info') }}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('gym.currency')}}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('gym.active_package')}}</th>
                                <th wire:click="sortBy('created_at')" 
                                    class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer">
                                    {{__('gym.created_at')}}
                                    @if($sortField === 'created_at')
                                        <span class="ml-1">
                                            {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                        </span>
                                    @endif
                                </th>
                                @if(auth()->user()->getCachedPermissions()->contains('edit_gym') || 
                                    auth()->user()->getCachedPermissions()->contains('delete_gym') || 
                                    auth()->user()->getCachedPermissions()->contains('view_gym') ||
                                    auth()->user()->getCachedPermissions()->contains('impersonate_admin'))
                                    <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider w-48">{{__('common.actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($gyms as $gym)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-xs sm:text-sm dark:text-gray-300">
                                    <td class="px-2 sm:px-4 py-3 sm:py-6">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 sm:h-10 sm:w-10">
                                                <img src="{{ $gym->logo_url }}" alt="Gym Logo" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                            </div>
                                            <div class="ml-2 sm:ml-4">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ $gym->name }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $gym->email }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $gym->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        {{ $gym->currency?->name ?? '-' }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        @if($gym->packageSubscriptions->isNotEmpty())
                                            @php
                                                $subscription = $gym->packageSubscriptions->first();
                                                $isActive = $subscription->is_active && $subscription->ends_at >= now();
                                            @endphp
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 text-xs sm:text-sm font-medium rounded-full {{ $isActive ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                        {{ $subscription->package->package_name }}
                                                    </span>
                                                </div>
                                                @if($subscription->ends_at)
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ __('gym.valid_until') }}: {{ $subscription->ends_at->format('Y-m-d') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-2">
                                                --
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        {{ $gym->created_at?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    @if(auth()->user()->getCachedPermissions()->contains('view_gyms') || 
                                        auth()->user()->getCachedPermissions()->contains('edit_gym') || 
                                        auth()->user()->getCachedPermissions()->contains('delete_gym') ||
                                        auth()->user()->getCachedPermissions()->contains('impersonate_admin'))
                                        <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm text-right font-medium">
                                            <div class="flex gap-2 sm:gap-1 justify-between items-center">
                                                @hasCachedPermission('view_gyms')
                                                    <a href="{{ route('gyms.show', $gym->id) }}" 
                                                        class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group"
                                                        aria-label="{{ __('common.view') }}">
                                                        <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('common.view') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                @endhasCachedPermission
                                                @hasCachedPermission('edit_gym')
                                                    <a href="{{ route('gyms.edit', $gym->id) }}" 
                                                        class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group"
                                                        aria-label="{{ __('common.edit') }}">
                                                        <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('common.edit') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                @endhasCachedPermission
                                                @hasCachedPermission('impersonate_admin')
                                                    <button wire:click="impersonateGym({{$gym->id}})"
                                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 relative group"
                                                        aria-label="{{ __('gym.impersonate') }}">
                                                        <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('gym.impersonate') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                    </button>
                                                @endhasCachedPermission
                                                @hasCachedPermission('assign_package')
                                                    <button wire:click="openAssignPackageModal({{ $gym->id }})"
                                                        class="text-gray-600 dark:text-gray-300 hover:text-green-600 relative group"
                                                        aria-label="{{ __('gym.assign_package') }}">
                                                        <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('gym.assign_package') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </button>
                                                @endhasCachedPermission
                                                @hasCachedPermission('delete_gym')
                                                    <button wire:click="handleDeleteGym({{$gym->id}})"
                                                        class="text-gray-600 dark:text-gray-300 hover:text-red-600 relative group"
                                                        aria-label="{{ __('common.delete') }}">
                                                        <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
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
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('gym.no_gyms') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $gyms->links() }}
        </div>
    </div>
</div>

@if($showAssignPackageModal && $selectedGymId)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-500 dark:bg-opacity-75" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg lg:max-w-2xl sm:w-full dark:bg-gray-800">
                <!-- Modal Header -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200" id="modal-title">
                                    {{ $currentPackage ? __('gym.update_package') : __('gym.assign_package') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="bg-white px-6 py-5 dark:bg-gray-800">
                    <div class="space-y-8">
                        @if($currentPackage)
                            <div class="overflow-hidden">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                        {{ __('gym.current_package') }}
                                    </h3>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('gym.package_name') }}</p>
                                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $currentPackage->package->package_name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('gym.start_date') }}</p>
                                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $currentPackage->starts_on->format('Y-m-d') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('gym.valid_until') }}</p>
                                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $currentPackage->ends_on ? $currentPackage->ends_on->format('Y-m-d') : 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('common.status') }}</p>
                                                <p class="mt-1 text-lg font-semibold {{ $currentPackage->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $currentPackage->is_active ? __('gym.active') : __('gym.inactive') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="overflow-visible">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-6">
                                    {{ $currentPackage ? __('gym.update_package') : __('gym.assign_package') }}
                                </h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="packageId" value="{{ __('gym.select_package') }}" class="text-sm mb-1 font-medium text-gray-700 dark:text-gray-300" />
                                        <x-dropdown align="left" width="w-full">
                                            <x-slot name="trigger">
                                                <button type="button" class="capitalize w-full relative text-left text-sm rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                                    {{ $packages->firstWhere('id', $packageId)->package_name ?? __('gym.select_package') }}
                                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1 dark:border dark:border-gray-700">
                                                    @foreach($packages as $package)
                                                        <x-dropdown-link 
                                                            :selected="$packageId == $package->id"
                                                            wire:click="$set('packageId', {{ $package->id }})"
                                                            @click="open = false"
                                                            class="dark:text-gray-300 dark:hover:bg-gray-700"
                                                        >
                                                            {{ $package->package_name . ($package->currency ? ' (' . $package->currency->code . ')' : '') }}
                                                        </x-dropdown-link>
                                                    @endforeach
                                                </div>
                                            </x-slot>
                                        </x-dropdown>
                                        <x-input-error for="packageId" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-label for="billingCycle" value="{{ __('gym.select_billing_cycle') }}" class="text-sm mb-1 font-medium text-gray-700 dark:text-gray-300" />
                                        <x-dropdown align="left" width="w-full">
                                            <x-slot name="trigger">
                                                <button type="button" class="capitalize w-full relative text-left text-sm rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                                    {{ isset($billingCycleOptions[$billingCycle]) ? $billingCycleOptions[$billingCycle]['label'] : __('gym.select_billing_cycle') }}
                                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1 dark:border dark:border-gray-700 capitalize">
                                                    @foreach($billingCycleOptions as $key => $option)
                                                        <x-dropdown-link 
                                                            :selected="$billingCycle == $key"
                                                            wire:click="$set('billingCycle', '{{ $key }}')"
                                                            @click="open = false"
                                                            class="dark:text-gray-300 dark:hover:bg-gray-700"
                                                        >
                                                            {{ $option['label'] }}
                                                        </x-dropdown-link>
                                                    @endforeach
                                                </div>
                                            </x-slot>
                                        </x-dropdown>
                                        <x-input-error for="billingCycle" class="mt-2" />
                                    </div>
                                    @php
                                        $selectedPackage = $packages->firstWhere('id', $packageId);
                                        $isTrialPackage = $selectedPackage && $selectedPackage->package_type === 'trial';
                                        $isLifetimePackage = $selectedPackage && $selectedPackage->package_type === 'lifetime';
                                    @endphp
                                    
                                    @if(!$isTrialPackage)
                                        <div>
                                            <x-label for="amount" value="{{ __('gym.amount') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                            <x-input wire:model="amount" id="amount" type="number" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            <x-input-error for="amount" class="mt-2" />
                                        </div>
                                    @endif

                                    <div>
                                        <x-label for="startsOn" value="{{ __('gym.start_date') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                        <x-input wire:model="startsOn" id="startsOn" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        <x-input-error for="startsOn" class="mt-2" />
                                    </div>

                                    @if(!$isLifetimePackage)
                                        <div>
                                            <x-label for="endsOn" value="{{ __('gym.end_date') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                            <x-input wire:model="endsOn" id="endsOn" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            <x-input-error for="endsOn" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-label for="expiresOn" value="{{ __('gym.expires_on') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                            <x-input wire:model="expiresOn" id="expiresOn" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            <x-input-error for="expiresOn" class="mt-2" />
                                        </div>
                                    @endif

                                    <div class="flex items-center">
                                        <x-checkbox wire:model="isActive" id="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                        <x-label for="isActive" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300" value="{{ __('gym.active') }}" />
                                    </div>

                                    <div class="flex items-center">
                                        <x-checkbox wire:model="paymentReceived" id="paymentReceived" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                        <x-label for="paymentReceived" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300" value="{{ __('gym.payment_received') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse sm:justify-start sm:space-x-reverse rounded-lg sm:space-x-3 dark:bg-gray-800">
                    <button wire:click="assignPackage" type="button"
                        class="w-full inline-flex justify-center items-center rounded-lg shadow-sm px-4 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm transition-colors duration-200 dark:bg-blue-500 dark:hover:bg-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('gym.assign') }}
                    </button>
                    <button wire:click="closeAssignPackageModal" type="button"
                        class="mt-3 w-full inline-flex justify-center items-center rounded-lg shadow-sm px-4 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('common.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
</div>