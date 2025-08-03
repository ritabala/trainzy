<div class="space-y-8">
    @if($subscription && $package)
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-8 dark:from-gray-800 dark:to-gray-900 dark:text-gray-200 relative border border-gray-100 dark:border-gray-700">
            {{-- Package Header with Status Badge --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-row sm:flex-col items-start ">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $package->package_name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium sm:mt-2 mt-1 sm:ml-0 ml-2
                        {{ $package->plan_type === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                        {{ ucfirst($package->plan_type) }}
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    @if(($subscription->billing_cycle == 'monthly' || $subscription->billing_cycle == 'annual')
                    && $stripeSubscription && $stripeSubscription->stripe_status == 'active')
                        <button wire:click="cancelSubscription" class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 border border-transparent rounded-lg font-medium text-sm text-white focus:outline-none focus:ring-1">
                            {{ __('package.cancel_subscription') }}
                        </button>
                    @endif

                    @if(auth()->user()->getCachedPermissions()->contains('upgrade_plan'))
                        <a href="{{ route('settings.billing.upgrade') }}"
                        class="inline-flex w-full sm:w-auto mt-4 sm:mt-0 items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-lg font-medium text-sm text-white focus:outline-none focus:ring-1">
                            {{ __('package.upgrade_plan') }}
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Package Details --}}
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('package.package_details') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.plan_type') }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $package->plan_type }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.billing_cycle') }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $subscription->billing_cycle }}</p>
                    </div>
                    @if($package->plan_type === 'paid')
                        @if($package->package_type === 'lifetime')
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.lifetime_plan_price') }}</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    @if($package->lifetime_price)
                                        {{ global_currency_format($subscription->amount, $package->currency_id) }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        @elseif($package->package_type === 'standard')
                            @if($subscription->billing_cycle === 'monthly')
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.monthly_plan_price') }}</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        @if($package->monthly_price)
                                            {{ global_currency_format($subscription->amount, $package->currency_id) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            @elseif($subscription->billing_cycle === 'annually')
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.annual_plan_price') }}</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        @if($package->annual_price)
                                            {{ global_currency_format($subscription->amount, $package->currency_id) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>

            {{-- Package Limits --}}
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('package.package_limits') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.max_members') }}</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $package->max_members }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.max_staff') }}</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $package->max_staff }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.max_classes') }}</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $package->max_classes }}</p>
                    </div>
                </div>
            </div>

            {{-- Subscription Period --}}
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('package.subscription_period') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.starts_on') }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $subscription->starts_on->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('package.ends_on') }}</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $subscription->ends_on ? $subscription->ends_on->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Package Modules --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-6 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('package.included_features') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modules as $module)
                        <div class="flex items-center space-x-3 bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                            <svg class="h-5 w-5 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ __('package.' . ($module->is_additional ? 'additional_modules_list.' : 'modules_list.') . $module->name) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center border border-gray-100 dark:border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('package.no_active_subscription') }}</p>
        </div>
    @endif
</div> 