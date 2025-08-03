<div>
    @if (session()->has('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50"
         role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if (session()->has('success'))
        <div x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif 

    <div class="space-y-8 mb-8">
        <!-- Header Section -->
        <div class="text-center space-y-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('package.choose_your_plan') }}</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                {{ __('package.select_plan_description') }}
            </p>
        </div>

        <!-- Currency and Billing Cycle Selectors -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-xl">
            <div class="flex justify-between items-start sm:flex-row flex-col">
                <!-- Billing Cycle Selector -->
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('package.billing_cycle') }}
                    </label>
                    <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-700 rounded-xl">
                        <button 
                            type="button"
                            wire:click="$set('selectedBillingCycle', 'monthly')"
                            class="px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $selectedBillingCycle === 'monthly' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                        >
                            {{ __('package.monthly_plan') }}
                        </button>
                        <button 
                            type="button"
                            wire:click="$set('selectedBillingCycle', 'annual')"
                            class="px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $selectedBillingCycle === 'annual' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
                        >
                            {{ __('package.annual_plan') }}
                        </button>
                    </div>
                </div>

                <!-- Currency Selector -->
                <div class="relative w-48 ml-8 sm:mt-0 mt-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('package.select_currency') }}
                    </label>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex justify-between w-full items-center px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm leading-4 font-medium rounded-xl shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-500 transition-all duration-200">
                                @if($selectedCurrencyId)
                                <span class="flex items-center">
                                    <span class="mr-2">{{ $selectedCurrency->symbol }}</span>
                                    <span class="block truncate">{{ $selectedCurrency->name }}</span>
                                </span>
                                @else
                                <span class="flex items-center">
                                    <span class="mr-2">{{ __('package.select_currency') }}</span>
                                </span>
                                @endif
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1 max-h-60 overflow-y-auto dark:border dark:border-gray-700 dark:bg-gray-500 dark:text-gray-200">
                                <x-dropdown-link 
                                    wire:click="$set('selectedCurrencyId', null)"
                                    :selected="$selectedCurrencyId === null"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    <span class="mr-2">{{ __('package.select_currency') }}</span>
                                </x-dropdown-link>
                                @foreach($currencies as $currency)
                                    <x-dropdown-link 
                                        wire:click="$set('selectedCurrencyId', {{ $currency->id }})"
                                        :selected="$selectedCurrencyId === $currency->id"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <span class="mr-2">{{ $currency->symbol }}</span>
                                        <span>{{ $currency->name }}</span>
                                        {{-- @if($selectedCurrencyId === $currency->id)
                                            <svg class="ml-auto h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @endif --}}
                                    </x-dropdown-link>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

    
        <!-- Package Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $package)
                <div 
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 flex flex-col border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] {{ $package->id === $currentPackageId ? 'ring-2 ring-indigo-500 dark:ring-indigo-400' : '' }}"
                    x-data="{ hover: false }"
                    @mouseenter="hover = true"
                    @mouseleave="hover = false"
                >
                    <!-- Package Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $package->package_name }}</h3>
                            {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 capitalize">Plan Type : {{ $package->plan_type }}</p> --}}
                        </div>
                        @if($package->id === $currentPackageId)
                            <span class="px-3 py-1 text-xs font-semibold bg-indigo-100  dark:bg-indigo-900 dark:text-indigo-200 rounded-full">
                                {{ __('package.current_plan') }}
                            </span>
                        @endif
                    </div>

                    <!-- Package Type Badge -->
                    <div class="mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $package->package_type === 'lifetime' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : ($package->package_type === 'standard' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                            {{ $package->package_type === 'lifetime' ? __('package.lifetime') : ($package->package_type === 'standard' ? __('package.standard') : __('package.free_plan')) }}
                        </span>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-8">
                        @if($package->package_type === 'lifetime')
                            <div class="flex items-baseline">
                                <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ global_currency_format($package->lifetime_price, $package->currency_id) ?? '-' }}</span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ __('package.lifetime') }}</span>
                            </div>
                        @elseif($package->package_type === 'standard')
                            <div class="space-y-2">
                                @if($package->monthly_price && $selectedBillingCycle === 'monthly')
                                    <div class="flex items-baseline">
                                        <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ global_currency_format($package->monthly_price, $package->currency_id) ?? '-' }}</span>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ __('package.monthly_plan') }}</span>
                                    </div>
                                @endif
                                @if($package->annual_price && $selectedBillingCycle === 'annual')
                                    <div class="flex items-baseline">
                                        <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ global_currency_format($package->annual_price, $package->currency_id) ?? '-' }}</span>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ __('package.annual_plan') }}</span>
                                    </div>
                                    {{-- <div class="text-sm text-green-600 dark:text-green-400">
                                        {{ __('package.save_20_percent') }}
                                    </div> --}}
                                @endif
                            </div>
                        @else
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('package.free_plan') }}</div>
                        @endif
                    </div>

                    <!-- Package Limits -->
                    <div class="mb-8 space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('package.max_members') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $package->max_members }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('package.max_staff') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $package->max_staff }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('package.max_classes') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $package->max_classes }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">{{ __('package.included_features') }}</h4>
                        <ul class="space-y-3">
                            @foreach($package->modules as $module)
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('package.' . ($module->is_additional ? 'additional_modules_list.' : 'modules_list.') . $module->name) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if ($offlinePaymentMethods->count() > 0 || $isStripeEnabled)
                    <!-- Action Button -->
                    <button 
                        wire:click="openPaymentModal({{ $package->id }})"
                        data-package-id="{{ $package->id }}"
                        @class([
                            'subscribe-button mt-auto w-full px-6 py-3 rounded-xl text-sm font-semibold text-white transition-all duration-200',
                            'bg-gray-400 dark:bg-gray-600 cursor-not-allowed' => $package->id === $currentPackageId && ($package->package_type === 'lifetime' ? true : $currentPackageFrequency == $selectedBillingCycle),
                            'bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600' => $package->id !== $currentPackageId || ($package->package_type === 'lifetime' ? false : $currentPackageFrequency != $selectedBillingCycle)
                        ])
                        @if($package->id === $currentPackageId && ($package->package_type === 'lifetime' ? true : $currentPackageFrequency == $selectedBillingCycle)) disabled @endif
                    >
                        {{ ($package->id === $currentPackageId && ($package->package_type === 'lifetime' ? true : $currentPackageFrequency == $selectedBillingCycle)) ? __('package.current_plan') : __('package.choose_plan') }}
                    </button>
                    @endif
                    
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payment Gateway Selection Modal -->
    <x-dialog-modal wire:model="showPaymentModal">
        <x-slot name="title">
            {{ __('package.select_payment_method') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Stripe Payment Option -->
                @if(config('cashier.key') && config('cashier.secret'))
                    @if($isStripeEnabled)
                        <button wire:click="selectPaymentGateway('stripe')"
                                wire:loading.attr="disabled"
                                class="w-full flex items-center justify-between px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.983 3.445 1.604 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z" fill="currentColor"/>
                                </svg>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('package.pay_with_stripe') }}
                                </span>
                            </div>
                            <div wire:loading wire:target="selectPaymentGateway('stripe')" class="ml-2">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif
                @endif

                <!-- Offline Payment Options -->
                @if($offlinePaymentMethods->count() > 0)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('package.offline_payment_methods') }}
                        </label>
                        @foreach($offlinePaymentMethods as $method)
                            <button wire:click="chooseOfflineMethod({{ $method->id }}); selectPaymentGateway('offline')"
                                    wire:loading.attr="disabled"
                                    class="w-full flex items-center justify-between px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $method->offline_method_name }}
                                    </span>
                                </div>
                                <div wire:loading wire:target="selectPaymentGateway('offline')" class="ml-2">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('common.cancel') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Offline Receipt Upload Modal -->
    <x-dialog-modal wire:model="showOfflineReceiptModal">
        <x-slot name="title">
            {{ __('package.offline_payment') }} : <span class="font-bold">{{ $selectedOfflineMethod ? \App\Models\GlobalPaymentGateway::find($selectedOfflineMethod)->offline_method_name : '' }}</span>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="submitOfflineReceipt" enctype="multipart/form-data">
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('package.attach_offline_receipt') }}
                    </p>
                    <div>
                        <input type="file" wire:model="offlineReceipt" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @error('offlineReceipt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('package.description') }}</label>
                        <textarea wire:model.defer="offlineDescription" rows="4" class="block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        @error('offlineDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('common.cancel') }}
            </x-secondary-button>
            <x-primary-button wire:click="submitOfflineReceipt" wire:loading.attr="disabled" class="sm:ml-3 ml-0">
                {{ __('common.save') }}
            </x-primary-button>
        </x-slot>
    </x-dialog-modal>
</div>