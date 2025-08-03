<div class="mx-auto p-6">
    <form wire:submit="updateAppSettings" class="space-y-6">
        <!-- Business Name and Logo Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('settings.app.business_details') }}</h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Business Name Input -->
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('settings.app.business_name') }}
                    </label>
                    <input 
                        type="text" 
                        id="app_name" 
                        wire:model.live="appName"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 
                               dark:bg-gray-700 dark:text-gray-300 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               transition duration-150 ease-in-out
                               placeholder-gray-400 dark:placeholder-gray-500
                               sm:text-sm"
                        placeholder="Enter business name"
                    >
                </div>

                <!-- Logo Upload with Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('settings.app.logo') }}
                    </label>
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Logo Preview -->
                        <div class="flex-shrink-0">
                            <div 
                                x-data="{ uploading: false }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                class="group relative h-40 w-40 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-50 dark:bg-gray-800/50 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors duration-200"
                            >
                                <!-- Loading Spinner -->
                                <div 
                                    x-show="uploading"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 bg-gray-50/80 dark:bg-gray-800/80 flex items-center justify-center z-10"
                                >
                                    <div class="flex flex-col items-center">
                                        <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>

                                @if ($appLogo && is_object($appLogo))
                                    <img 
                                        src="{{ $appLogo->temporaryUrl() }}" 
                                        alt="Logo preview" 
                                        class="h-full w-full object-contain p-3"
                                    >
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ __('settings.app.change_logo') }}</span>
                                    </div>
                                @elseif ($appSettings->logo_url)
                                    <img 
                                        src="{{ $appSettings->logo_url }}" 
                                        alt="Current logo" 
                                        class="h-full w-full object-contain p-3"
                                    >
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ __('settings.app.change_logo') }}</span>
                                    </div>
                                @else
                                    <div class="h-full w-full flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm">{{ __('settings.app.upload_logo') }}</span>
                                    </div>
                                @endif
                                
                                <label for="app_logo" class="absolute inset-0 cursor-pointer">
                                    <input 
                                        type="file" 
                                        id="app_logo" 
                                        wire:model.live="appLogo"
                                        accept="image/*"
                                        class="sr-only"
                                    >
                                </label>
                            </div>
                            
                            <div class="mt-2 text-center">
                              
                                @error('appLogo') 
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload Info -->
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ __('settings.app.logo_requirements') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('settings.app.logo_requirements_description') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>{{ __('settings.app.logo_requirements_description_2') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('settings.app.currency_settings') }}</h2>
            </div>
            
            <div class="p-6">
                <!-- Currency Select -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('settings.app.currency') }}
                    </label>
                    <select 
                        id="currency" 
                        wire:model.live="currency"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 
                               dark:bg-gray-700 dark:text-gray-300 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               transition duration-150 ease-in-out
                               sm:text-sm"
                    >
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" class="py-2">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit"
                class="inline-flex items-center px-4 py-2.5 border border-transparent 
                       rounded-lg shadow-sm text-sm font-medium text-white 
                       bg-indigo-600 hover:bg-indigo-700 
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                       transition duration-150 ease-in-out
                       dark:focus:ring-offset-gray-800"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ __('settings.app.save') }}
            </button>
        </div>
    </form>
</div>
