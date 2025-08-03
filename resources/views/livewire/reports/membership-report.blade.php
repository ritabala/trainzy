<div class="space-y-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    {{-- Header Section --}}
    <div class="flex lg:items-center gap-4 flex-col lg:flex-row justify-between mb-4">
        <div>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('reports.membership.description') }}</p>
        </div>
            <button
                wire:click="exportToCSV"
                wire:loading.attr="disabled"
                class="inline-flex bg-blue-500 dark:bg-blue-600 text-white items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md hover:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span wire:loading.remove>{{ __('common.export') }}</span>
                <span wire:loading>{{ __('common.exporting') }}</span>
            </button>
    </div>

    {{-- Filters Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg">
        <div class="p-4">
            <div class="flex flex-wrap items-end gap-4">
                {{-- Date Range Filter --}}
                <div class="w-48">
                    <label for="dateRange" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{ __('reports.filters.date_range') }}</label>
                    <x-dropdown align="left" width="48" :selectedValue="$dateRange">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="block truncate">
                                    {{ match($dateRange) {
                                        'this_month' => __('reports.membership.date_range.this_month'),
                                        'last_month' => __('reports.membership.date_range.last_month'),
                                        'last_3_months' => __('reports.membership.date_range.last_3_months'),
                                        'last_6_months' => __('reports.membership.date_range.last_6_months'),
                                        'this_year' => __('reports.membership.date_range.this_year'),
                                        'custom' => __('reports.membership.date_range.custom'),
                                        default => __('common.select')
                                    } }}
                                </span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 dark:border-gray-700 border">
                                <x-dropdown-link :selected="$dateRange === 'this_month'" wire:click="$set('dateRange', 'this_month')">
                                    {{ __('reports.membership.date_range.this_month') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$dateRange === 'last_month'" wire:click="$set('dateRange', 'last_month')">
                                    {{ __('reports.membership.date_range.last_month') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$dateRange === 'last_3_months'" wire:click="$set('dateRange', 'last_3_months')">
                                    {{ __('reports.membership.date_range.last_3_months') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$dateRange === 'last_6_months'" wire:click="$set('dateRange', 'last_6_months')">
                                    {{ __('reports.membership.date_range.last_6_months') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$dateRange === 'this_year'" wire:click="$set('dateRange', 'this_year')">
                                    {{ __('reports.membership.date_range.this_year') }}
                                </x-dropdown-link>
                                <x-dropdown-link :selected="$dateRange === 'custom'" wire:click="$set('dateRange', 'custom')">
                                    {{ __('reports.membership.date_range.custom') }}
                                </x-dropdown-link>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Custom Date Range --}}
                <div class="flex items-center gap-2">
                    <div class="w-40">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{ __('reports.filters.from_date') }}</label>
                        <input
                            type="date"
                            id="startDate"
                            wire:model.live="startDate"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                        >
                    </div>
                    <span class="text-gray-500 dark:text-gray-400 mt-5">to</span>
                    <div class="w-40">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{ __('reports.filters.to_date') }}</label>
                        <input
                            type="date"
                            id="endDate"
                            wire:model.live="endDate"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                        >
                    </div>
                </div>

                {{-- Search --}}
                <div class="w-52">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">{{ __('common.search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input
                            type="search"
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            class="w-full pl-9 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                            placeholder="{{ __('membership.search') }}"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                            <div class="flex items-center space-x-1">
                                <span>{{ __('reports.membership.name') }}</span>
                                @if ($sortField === 'name')
                                    <span class="text-gray-400">
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('reports.membership.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('reports.membership.active_customers') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('reports.membership.lost_customers') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('reports.membership.renewals') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($membershipStats as $stat)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $stat->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $stat->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $stat->is_active ? __('common.active') : __('common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ number_format($stat->active_customers) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ number_format($stat->lost_customers) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ number_format($stat->renewals) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center bg-gray-50 dark:bg-gray-700">
                                {{ __('reports.membership.no_data_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center">
                <div class="items-center space-x-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.show') }}</span>
                    <select
                        wire:model.live="perPage"
                        class="rounded-md px-2 py-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('common.entries') }}</span>
                </div>
                <div class="grid grid-cols-1">
                    {{ $membershipStats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>