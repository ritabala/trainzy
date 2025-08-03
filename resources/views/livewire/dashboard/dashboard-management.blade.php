<div class="min-h-screen px-4 lg:px-0">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Members Overview Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.members') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalMembers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Classes Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.classes') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $activeClasses ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.monthly_revenue') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ currency_format($monthlyRevenue) }}</p>
                </div>
            </div>
        </div>

        <!-- Staff Schedule Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.staff') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalStaff ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Unpaid Invoices Card -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.stats.unpaid_invoices') }}</h2>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ currency_format($unpaidInvoicesAmount) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Quick Actions -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('dashboard.quick_actions.title') }}</h3>
            <div class="space-y-2">
                <a href="{{ route('members.create') }}" class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('dashboard.quick_actions.add_member') }}
                </a>
                <a href="{{ route('staff.create') }}" class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('dashboard.quick_actions.add_staff') }}
                </a>
                <a href="{{ route('invoices.create') }}" class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('dashboard.quick_actions.create_invoice') }}
                </a>
                <a href="{{ route('payments.create') }}" class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('dashboard.quick_actions.add_payment') }}
                </a>
                <a href="{{ route('memberships.create') }}" class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    {{ __('dashboard.quick_actions.create_membership') }}
                </a>
            </div>
        </div>

        <!-- Recent Members -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('dashboard.recent_members') }}</h3>
            <div class="space-y-2">
                @if(isset($recentMembers) && count($recentMembers) > 0)
                    @foreach($recentMembers as $member)
                        <div class="flex items-center justify-between p-2 text-sm transition-colors rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $member->name ?? 'Unknown Member' }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ $member->email ?? 'No Email' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 rounded-full">
                                {{ $member->created_at->format('d M Y') ?? 'Unknown' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_recent_members') }}</p>
                @endif
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
            <h3 class="mb-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('dashboard.recent_payments') }}</h3>
            <div class="space-y-2">
                @if(isset($recentPayments) && count($recentPayments) > 0)
                    @foreach($recentPayments as $payment)
                        <div class="flex items-center justify-between p-2 text-sm transition-colors rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $payment->user->name ?? 'Unknown Member' }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ currency_format($payment->amount_paid) }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs capitalize font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900 rounded-full">
                                {{ $payment->status ?? 'Unknown' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.no_recent_payments') }}</p>
                @endif
            </div>
        </div>
    </div>

   
</div> 