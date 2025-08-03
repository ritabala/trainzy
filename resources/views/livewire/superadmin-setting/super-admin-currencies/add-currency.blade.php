<div>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-500 dark:bg-opacity-75" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 sm:mt-5">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-gray-100 mb-4">{{ __('settings.currencies.add_new') }}</h3>
                        <div>
                            <form wire:submit="save" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.name') }}</label>
                                        <input type="text" wire:model.live="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('name') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.code') }}</label>
                                        <input type="text" wire:model.live="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('code') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.symbol') }}</label>
                                        <input type="text" wire:model.live="symbol" id="symbol" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('symbol') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="decimal_places" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.decimal_places') }}</label>
                                        <input type="number" wire:model.live="decimal_places" id="decimal_places" min="0" max="10" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('decimal_places') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="decimal_point" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.decimal_point') }}</label>
                                        <input type="text" wire:model.live="decimal_point" id="decimal_point" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('decimal_point') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="thousands_separator" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.currencies.thousands_separator') }}</label>
                                        <input type="text" wire:model.live="thousands_separator" id="thousands_separator" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                                        @error('thousands_separator') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Live Preview Section -->
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.currencies.preview') }}</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('settings.currencies.small_amount') }}</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                                @if($symbol && $decimal_point && $thousands_separator && is_numeric($decimal_places))
                                                    {{ $symbol }}{{ number_format(1234.56, (int)$decimal_places, $decimal_point, $thousands_separator) }}
                                                @else
                                                    {{ __('settings.currencies.preview_will_appear') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('settings.currencies.large_amount') }}</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                                @if($symbol && $decimal_point && $thousands_separator && is_numeric($decimal_places))
                                                    {{ $symbol }}{{ number_format(1234567.89, (int)$decimal_places, $decimal_point, $thousands_separator) }}
                                                @else
                                                    {{ __('settings.currencies.preview_will_appear') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 sm:col-start-2">{{ __('settings.currencies.add') }}</button>
                                    <button type="button" wire:click="$dispatch('close-add-currency')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0">{{ __('common.cancel') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
