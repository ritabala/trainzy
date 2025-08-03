<div class="min-h-screen">
    <div class="mx-auto max-w-screen-2xl  2xl:p-10">
        <div class="mx-auto max-w-270">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-black dark:text-white">
                        {{ __('header.account_settings') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('header.account_settings_description') }}
                    </p>
                </div>
            </div>

            <!-- Account Settings Form -->
            <div class="grid gap-8 md:grid-cols-2">
                <!-- Password Change Card -->
                <div class="rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-black dark:text-white">
                            {{ __('header.change_password') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('header.change_password_description') }}
                        </p>
                    </div>

                    @if (session()->has('success'))
                        <div class="mb-6 rounded-lg border border-success bg-success/10 px-4 py-3 text-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-6">
                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                                {{ __('header.current_password') }} <span class="text-meta-1">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    wire:model="current_password"
                                    placeholder="{{ __('header.current_password_placeholder') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    x-ref="currentPasswordInput"
                                />
                                <button type="button" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary dark:text-gray-400"
                                    x-data="{ show: false }"
                                    @click="show = !show; $refs.currentPasswordInput.type = show ? 'text' : 'password'">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                                {{ __('header.new_password') }} <span class="text-meta-1">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    wire:model="new_password"
                                    placeholder="{{ __('header.new_password_placeholder') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    x-ref="newPasswordInput"
                                />
                                <button type="button" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary dark:text-gray-400"
                                    x-data="{ show: false }"
                                    @click="show = !show; $refs.newPasswordInput.type = show ? 'text' : 'password'">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-2.5 block text-sm font-medium text-black dark:text-white">
                                {{ __('header.new_password_confirmation') }} <span class="text-meta-1">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    wire:model="new_password_confirmation"
                                    placeholder="{{ __('header.new_password_confirmation_placeholder') }}"
                                    class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    x-ref="confirmPasswordInput"
                                />
                                <button type="button" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary dark:text-gray-400"
                                    x-data="{ show: false }"
                                    @click="show = !show; $refs.confirmPasswordInput.type = show ? 'text' : 'password'">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3 font-medium text-white transition-all duration-300 ease-in-out hover:bg-opacity-90 hover:shadow-lg"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            {{ __('header.update_password') }}
                        </button>
                    </form>
                </div>

                <!-- Account Information Card -->
                <div class="rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-black dark:text-white">
                            {{ __('header.password_requirements') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('header.password_requirements_description') }}
                        </p>
                    </div>

                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('header.at_least_8_characters_long') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('header.contains_at_least_one_uppercase_letter') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('header.contains_at_least_one_number') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('header.contains_at_least_one_special_character') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
