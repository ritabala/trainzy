<div class="">
    <form wire:submit.prevent="save">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4 dark:text-gray-200">{{ __('settings.app.title') }}</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.business_name') }}</label>
                        <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.phone') }}</label>
                        <input type="tel" wire:model="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.website') }}</label>
                        <input type="url" wire:model="website" id="website" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.language') }}</label>
                        <select wire:model="locale" id="locale" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="en">{{ __('settings.app.english') }}</option>
                            <option value="es">{{ __('settings.app.spanish') }}</option>
                            <option value="fr">{{ __('settings.app.french') }}</option>
                            <!-- Add more languages as needed -->
                        </select>
                        @error('locale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.logo') }}</label>
                        <div class="mt-1 flex items-center gap-3">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Current logo" class="h-10 w-auto object-contain rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @endif
                            <div class="flex-1">
                                <input type="file" wire:model="tempLogo" id="logo" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800" accept="image/*">
                                @error('tempLogo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @if($logo)
                                <button type="button" wire:click="removeLogo" class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.email') }}</label>
                        <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.currency') }}</label>
                        <select wire:model="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                            @endforeach
                            <!-- Add more currencies as needed -->
                        </select>
                        @error('currency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.timezone') }}</label>
                        <select wire:model="timezone" id="timezone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone }}">{{ $timezone }}</option>
                            @endforeach
                            <!-- Add more timezones as needed -->
                        </select>
                        @error('timezone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>

            <!-- Address Field (Full Width) -->
            <div class="mt-4">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.app.address') }}</label>
                <textarea wire:model="address" id="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 dark:bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ __('settings.app.save') }}
                </button>
            </div>
        </div>
    </form>
</div>
