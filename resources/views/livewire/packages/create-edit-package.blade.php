<form wire:submit.prevent="save">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
            <div class="space-y-6">
                @if(!($this->package_type == 'trial' || $this->package_type == 'default'))
                    <!-- Plan Type Selection -->
                    <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('package.plan_type') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Paid Plan Option -->
                            <label class="flex items-center p-4 bg-white dark:bg-gray-700 rounded-md shadow-sm hover:shadow cursor-pointer group border border-gray-200 dark:border-gray-600">
                                <input type="radio" wire:model="plan_type" value="paid" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ __('package.paid_plan') }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('package.paid_plan_desc') }}</p>
                                </div>
                            </label>

                            <!-- Free Plan Option -->
                            <label class="flex items-center p-4 bg-white dark:bg-gray-700 rounded-md shadow-sm hover:shadow cursor-pointer group border border-gray-200 dark:border-gray-600">
                                <input type="radio" wire:model="plan_type" value="free" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ __('package.free_plan') }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('package.free_plan_desc') }}</p>
                                </div>
                            </label>
                        </div>
                        @error('plan_type') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                @endif

                <!-- Package Name -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <label for="package_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.name') }}</label>
                    <input type="text" wire:model="package_name" id="package_name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error('package_name') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                @if($package_type == 'trial')
                    <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.trial_days') }}</label>
                            <input type="number" wire:model="trial_days" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('trial_days') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.notification_before_days') }}</label>
                            <input type="number" wire:model="notification_before_days" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">  
                            @error('notification_before_days') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.trial_message') }}</label>
                            <input type="text" wire:model="trial_message" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('trial_message') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <!-- Paid Plan Fields -->
                <div x-data="{ plan_type: @entangle('plan_type'), package_type: @entangle('package_type'), monthly_price_status: @entangle('monthly_price_status'), annual_price_status: @entangle('annual_price_status'), lifetime_price_status: @entangle('lifetime_price_status') }">
                    <template x-if="plan_type === 'paid'">
                        <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600 space-y-5">
                            <!-- Package Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.package_type') }}</label>
                                <x-dropdown align="left" width="48" :selectedValue="$package_type">
                                    <x-slot name="trigger">
                                        <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="block truncate">
                                                {{ $package_type === 'lifetime' ? __('package.lifetime') : __('package.standard') }}
                                            </span>
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :selected="$package_type === 'standard'" wire:click="$set('package_type', 'standard')">{{ __('package.standard') }}</x-dropdown-link>
                                        <x-dropdown-link :selected="$package_type === 'lifetime'" wire:click="$set('package_type', 'lifetime')">{{ __('package.lifetime') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                                @error('package_type') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            @if(
                            $package_type != 'default' && $package_type != 'trial' &&
                            (!$package || (is_null($package->stripe_monthly_price_id) && is_null($package->stripe_annual_price_id) && is_null($package->stripe_lifetime_price_id))))
                            <!-- Currency -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.currency') }}</label>
                                <x-dropdown align="left" width="48" :selectedValue="$currency_id">
                                    <x-slot name="trigger">
                                        <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="block truncate">{{ $currency_id ? $global_currencies->find($currency_id)->name : __('package.select_currency') }}</span>
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        @foreach($global_currencies as $currency)
                                            <x-dropdown-link :selected="$currency_id == $currency->id" wire:click="$set('currency_id', '{{ $currency->id }}')">
                                                {{ $currency->name }}
                                            </x-dropdown-link>
                                        @endforeach
                                    </x-slot>
                                </x-dropdown>
                                @error('currency_id') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                            @elseif($package->currency)
                                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('gym.currency') }}</label>
                                    <span class="block truncate">{{ $package->currency->name . ' (' . $package->currency->code . ')' }}</span>
                                    <span class="block truncate text-xs text-gray-500 dark:text-gray-400">{{ __('gym.note_currency_cannot_be_changed') }}</span>
                                </div>
                                <input type="hidden" wire:model="currency_id" value="{{ $package->currency_id }}">
                            @endif

                            <!-- Price Fields -->
                            <template x-if="package_type === 'standard'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-md shadow-sm">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model.defer="monthly_price_status" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('package.monthly_plan') }}</span>
                                        </label>
                                        <div x-show="monthly_price_status" class="mt-3">
                                            <input type="number" step="0.01" min="0" wire:model="monthly_price" placeholder="{{ __('package.monthly_plan_price') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('monthly_price') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        @if($paymentGateways->stripe_status)
                                        <div x-show="monthly_price_status" class="mt-3">
                                            <input type="text" wire:model="stripe_monthly_price_id" placeholder="{{ __('package.stripe_price_id') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('stripe_monthly_price_id') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        @endif
                                    </div>
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-md shadow-sm">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model.defer="annual_price_status" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('package.annual_plan') }}</span>
                                        </label>
                                        <div x-show="annual_price_status" class="mt-3">
                                            <input type="number" step="0.01" min="0" wire:model="annual_price" placeholder="{{ __('package.annual_plan_price') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('annual_price') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>

                                        @if($paymentGateways->stripe_status)
                                        <div x-show="annual_price_status" class="mt-3">
                                            <input type="text" wire:model="stripe_annual_price_id" placeholder="{{ __('package.stripe_price_id') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('stripe_annual_price_id') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        @error('price_status_check') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </template>
                            <template x-if="package_type === 'lifetime'">
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-md shadow-sm flex flex-col sm:flex-row gap-4 items-center w-full">
                                    <div class="w-full">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.lifetime_plan_price') }}</label>
                                        <div class="mt-2">
                                            <input type="number" step="0.01" min="0" wire:model="lifetime_price" placeholder="{{ __('package.lifetime_plan_price') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('lifetime_price') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    @if($paymentGateways->stripe_status)
                                    <div class="w-full">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.stripe_price_id') }}</label>
                                        <div class="mt-2">
                                            <input type="text" wire:model="stripe_lifetime_price_id" placeholder="{{ __('package.stripe_price_id') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('stripe_lifetime_price_id') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        </div>
                                    @endif
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Modules -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('package.modules') }}</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($all_modules as $module)
                            <label class="flex items-center p-3 bg-white dark:bg-gray-700 rounded-md shadow-sm hover:shadow cursor-pointer group">
                                <input type="checkbox" wire:model="selected_modules" value="{{ $module->id }}" class="form-checkbox h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 capitalize">{{ Str::replace('_', ' ', $module->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selected_modules') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Additional Modules -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('package.additional_modules') }}</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($all_additional_modules as $module)
                            <label class="flex items-center p-3 bg-white/50 dark:bg-gray-700/50 rounded-md shadow-sm hover:shadow cursor-pointer group border border-gray-100 dark:border-gray-600">
                                <input type="checkbox" wire:model="selected_additional_modules" value="{{ $module->id }}" class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 capitalize">{{ Str::replace('_', ' ', $module->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selected_additional_modules') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Max Members/Staff/Classes -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.max_members') }}</label>
                            <input type="number" wire:model="max_members" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('max_members') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.max_staff') }}</label>
                            <input type="number" wire:model="max_staff" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('max_staff') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.max_classes') }}</label>
                            <input type="number" wire:model="max_classes" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('max_classes') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <!-- Is Active -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('package.is_active') }}</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="bg-gray-50/50 dark:bg-gray-700/30 p-5 rounded-lg border border-gray-100 dark:border-gray-600">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('package.description') }}</label>
                    <textarea wire:model="description" id="description" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    @error('description') <span class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="cancel" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-gray-500">
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500">
                    {{ __('common.save') }}
                </button>
            </div>
        </div>
    </div>
</form> 