<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <div class="p-6">
        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <div class="w-full md:w-1/3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('package.search_placeholder') }}" 
                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>
            <div class="w-full md:w-1/4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        <span>{{ $status ? __('package.status.' . $status) : __('package.all_status') }}</span>
                        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-gray-700">
                        <div class="py-1">
                            <button wire:click="$set('status', '')" @click="open = false" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('package.all_status') }}
                            </button>
                            <button wire:click="$set('status', 'active')" @click="open = false" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('package.status.active') }}
                            </button>
                            <button wire:click="$set('status', 'inactive')" @click="open = false" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('package.status.inactive') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th wire:click="sortBy('id')" class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer">
                            ID
                            @if($sortField === 'id')
                                <span class="ml-1">
                                    {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                </span>
                            @endif
                        </th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('package.name') }}</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('package.monthly_plan_price') }}</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('package.annual_plan_price') }}</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('package.lifetime_plan_price') }}</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">Modules</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('package.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($packages as $package)
                        @php
                            $isTrialOrDefault = $package->package_type == 'trial' || $package->package_type == 'default';
                            $currencySymbol = $package->currency?->symbol ?? '$';
                            $decimalPlaces = $package->currency?->decimal_places ?? 2;
                            $decimalPoint = $package->currency?->decimal_point ?? '.';
                            $thousandsSeparator = $package->currency?->thousands_separator ?? ',';
                            
                            $monthlyPrice = $isTrialOrDefault ? '--' : $currencySymbol . number_format($package->monthly_price, $decimalPlaces, $decimalPoint, $thousandsSeparator);
                            $annualPrice = $isTrialOrDefault ? '--' : $currencySymbol . number_format($package->annual_price, $decimalPlaces, $decimalPoint, $thousandsSeparator);
                            $lifetimePrice = $isTrialOrDefault ? '--' : $currencySymbol . number_format($package->lifetime_price, $decimalPlaces, $decimalPoint, $thousandsSeparator);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                            <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">{{ $package->id }}</td>
                            <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">{{ $package->package_name }}</td>
                            <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">{{ $monthlyPrice }}</td>
                            <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">{{ $annualPrice }}</td>
                            <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">{{ $lifetimePrice }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $allModules = \App\Models\Module::all();
                                        $selectedModuleIds = $package->modules->pluck('id')->toArray();
                                        
                                        // Sort modules: selected first, then unselected
                                        $sortedModules = $allModules->sortBy(function($module) use ($selectedModuleIds) {
                                            return in_array($module->id, $selectedModuleIds) ? 0 : 1;
                                        });
                                    @endphp
                                    @foreach($sortedModules as $module)
                                        <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs capitalize font-medium {{ in_array($module->id, $selectedModuleIds) ? 'bg-green-50 dark:bg-green-900/50' : 'bg-red-50 dark:bg-red-900/50' }} text-gray-700 dark:text-gray-300">
                                            @if(in_array($module->id, $selectedModuleIds))
                                                <svg class="w-4 h-4 mr-1.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 mr-1.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @endif
                                            {{ Str::replace('_', ' ', $module->name) }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('packages.edit', $package->id) }}" 
                                        class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group"
                                        aria-label="{{ __('common.edit') }}">
                                        <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.edit') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg> 
                                    </a>
                                    @if(!$isTrialOrDefault)
                                        <button wire:click="handleDeletePackage({{$package->id}})"
                                            class="text-gray-600 dark:text-gray-300 hover:text-red-600 relative group"
                                            aria-label="{{ __('common.delete') }}">
                                            <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                {{ __('common.delete') }}
                                            </span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm font-medium">{{ __('package.messages.no_packages') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $packages->links() }}
        </div>
    </div>
</div> 