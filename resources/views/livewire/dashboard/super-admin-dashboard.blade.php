<div class="min-h-screen px-4 lg:px-0">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Gyms Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400"> {{ __('user.total_gyms') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalGyms }}</p>
                </div>
            </div>
        </div>

        <!-- Today Gyms Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400"> {{ __('user.today_gyms') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $todayGyms }}</p>
                </div>
            </div>
        </div>

        <!-- Total Active Packages Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='w-6 h-6 text-blue-600 dark:text-blue-400' viewBox='0 0 16 16'>
                        <path d='M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z'/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400"> {{ __('user.total_active_packages') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalActivePackages }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Gyms -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4"> {{ __('user.recent_gyms') }}</h2>
        
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('gym.gym_info') }}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('gym.currency')}}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('gym.active_package')}}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('gym.created_at')}}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($recentGyms as $gym)
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
                                        {{ $gym->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-2 sm:px-4 py-3 sm:py-6 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('gym.no_gyms') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div> 