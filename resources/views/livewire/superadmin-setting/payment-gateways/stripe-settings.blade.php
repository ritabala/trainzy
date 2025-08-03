<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('settings.payment_gateways.stripe.title') }}</h3>
        </div>
        <form wire:submit.prevent="saveSettings" class="space-y-4 sm:space-y-6">
            <div class="flex items-center mb-4">
                <input type="checkbox" wire:model.live="stripeStatus" id="enableStripe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                <label for="enableStripe" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.payment_gateways.stripe.enable') }}</label>
            </div>

            @if($stripeStatus)
                <div>
                    <label for="environment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.payment_gateways.stripe.select_environment') }}</label>
                    <select wire:model.live="stripeEnvironment" id="stripeEnvironment" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="test">{{ __('settings.payment_gateways.stripe.environment.test') }}</option>
                        <option value="live">{{ __('settings.payment_gateways.stripe.environment.live') }}</option>
                    </select>
                    @error('stripeEnvironment') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-2">
                    <a 
                    @if($stripeEnvironment === 'test')
                        href="https://dashboard.stripe.com/test/apikeys" 
                    @else
                        href="https://dashboard.stripe.com/apikeys" 
                    @endif
                    target="_blank" class="text-blue-600 dark:text-blue-400 text-sm hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                            <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                          </svg>
                        <span class="ml-2">{{ __('settings.payment_gateways.stripe.get_credentials') }}</span>
                    </a>
                </div>

                <div class="mt-4">
                    <label for="stripeKey" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $stripeEnvironment === 'test' ? __('settings.payment_gateways.stripe.environment.test') . ' ' : '' }}{{ __('settings.payment_gateways.stripe.key') }}
                    </label>
                    <input type="text" wire:model="stripeKey" id="stripeKey" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('stripeKey') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <label for="stripeSecret" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $stripeEnvironment === 'test' ? __('settings.payment_gateways.stripe.environment.test') . ' ' : '' }}{{ __('settings.payment_gateways.stripe.secret') }}
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" wire:model="stripeSecret" id="stripeSecret" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1">
                            <svg x-show="!show" class="h-5 w-5 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-5 w-5 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('stripeSecret') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <label for="stripeWebhookKey" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $stripeEnvironment === 'test' ? __('settings.payment_gateways.stripe.environment.test') . ' ' : '' }}{{ __('settings.payment_gateways.stripe.webhook_key') }}
                    </label>
                    <input type="text" wire:model="stripeWebhookKey" id="stripeWebhookKey" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('stripeWebhookKey') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <label for="webhookUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('settings.payment_gateways.stripe.webhook_url') }}</label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <input type="text" id="webhookUrl" readonly value="{{ url('/webhook/billing-verify-webhook/' . (auth()->user()->id ?? '')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhookUrl').value)" class="w-full sm:w-auto px-3 py-1 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-500">{{ __('common.copy') }}</button>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="w-full sm:w-32 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                    {{ __('common.save') }}
                </button>
            </div>
        </form>
    </div>
</div> 