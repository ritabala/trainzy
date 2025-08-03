<div class="space-y-4 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    {{-- Header Section --}}
    <div class="flex lg:items-center gap-4 flex-col lg:flex-row justify-between mb-4">
        <div>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('reports.revenue.description') }}</p>
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
                {{-- Year Filter --}}
                <div class="w-48">
                    <label for="selectedYear" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reports.revenue.year') }}</label>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500 text-sm text-left px-3 py-2 bg-white dark:bg-gray-700 dark:text-gray-300">
                            {{ $selectedYear }}
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 rounded-md shadow-lg">
                            <div class="py-1 dark:border-gray-700 border">
                                @foreach($years as $yearOption)
                                <x-dropdown-link :selected="$selectedYear == $yearOption" wire:click="$set('selectedYear', '{{ $yearOption }}'); open = false" 
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    {{ $yearOption }}
                                </x-dropdown-link>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search --}}
                <div class="w-52">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.search') }}</label>
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
                            class="w-full pl-9 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm"
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
                        <th wire:click="sortBy('membership_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 sticky left-0 bg-gray-50 dark:bg-gray-700">
                            <div class="flex items-center space-x-1">
                                <span>{{ __('reports.revenue.membership') }}</span>
                                @if ($sortField === 'membership_name')
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
                        @for ($month = 1; $month <= 12; $month++)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ Carbon\Carbon::create()->month($month)->format('M') }}
                            </th>
                        @endfor
                        <th wire:click="sortBy('year_total')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 bg-blue-50 dark:bg-blue-900">
                            <div class="flex items-center space-x-1">
                                <span>{{ __('reports.revenue.year_total') }}</span>
                                @if ($sortField === 'year_total')
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
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($revenueStats['revenue_data'] as $stat)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $stat['membership_name'] }}</div>
                            </td>
                            @foreach($stat['monthly_data'] as $amount)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ currency_format($amount) }}</div>
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap bg-blue-50 dark:bg-blue-900">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ currency_format($stat['year_total']) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center bg-gray-50 dark:bg-gray-700">
                                {{ __('reports.revenue.no_data_found') }}
                            </td>
                        </tr>
                    @endforelse
                    {{-- Monthly Totals Row --}}
                    <tr class="bg-gray-100 dark:bg-gray-700 font-medium">
                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-gray-100 dark:bg-gray-700">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('reports.revenue.monthly_total') }}</div>
                        </td>
                        @foreach($revenueStats['monthly_totals'] as $total)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ currency_format($total) }}</div>
                            </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap bg-blue-100 dark:bg-blue-800">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ currency_format($revenueStats['grand_total']) }}</div>
                        </td>
                    </tr>
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
                    {{ $revenueStats['revenue_data']->links() }}
                </div>
            </div>
        </div>
    </div>
</div>