<div>
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6">
            @if(session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.qr_codes') }}</h2>
                <div class="flex space-x-2">
                    @if(auth()->user()->getCachedPermissions()->contains('download_qr_code'))
                        <button wire:click="exportAllQrCodes" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 dark:focus:bg-green-600 active:bg-green-900 dark:active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            {{ __('attendance.export_all') }}
                        </button>
                        <button wire:click="exportPdf" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('attendance.export_pdf') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <div class="flex flex-col sm:flex-row flex-wrap items-end justify-start gap-4 pb-7 border-b border-gray-200 dark:border-gray-700">
                    <!-- Search -->
                    <div class="relative w-full sm:w-64 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="{{ __('attendance.search_placeholder') }}" 
                            class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-400 dark:border-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
                        >
                    </div>

                    <!-- Role Type Filter -->
                    <div class="relative w-full sm:w-64 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
                        <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{ __('common.role') }}</div>
                        <x-dropdown align="left" width="auto">
                            <x-slot name="trigger">
                                <button type="button" class="w-full sm:w-64 inline-flex justify-between items-center px-3 py-2 border dark:border-gray-700 dark:bg-gray-800 dark:text-white border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
                                    <span>
                                        {{ $roleType === '' ? __('common.all') : $allRoles->where('name', $roleType)->first()['display_name'] }}
                                    </span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1 dark:border dark:border-gray-700 dark:bg-gray-500 dark:text-gray-200">
                                    <x-dropdown-link wire:click="$set('roleType', '')" :selected="$roleType === ''">
                                        {{ __('common.all') }}
                                    </x-dropdown-link>
                                    @foreach($allRoles as $role)
                                        <x-dropdown-link wire:click="$set('roleType', '{{ $role->name }}')" :selected="$roleType == '{{ $role->name }}'">
                                            {{ $role->display_name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Date From Filter -->
                    <div class="relative w-full sm:w-64 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
                        <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{ __('attendance.date_from') }}</div>
                        <input 
                            type="date" 
                            wire:model.live="dateFrom" 
                            class="w-full py-1.5 text-sm rounded-md border-gray-300 disabled:dark:bg-gray-400 dark:border-gray-700 dark:text-white dark:bg-gray-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <!-- Date To Filter -->
                    <div class="relative w-full sm:w-64 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
                        <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{ __('attendance.date_to') }}</div>
                        <input 
                            type="date" 
                            wire:model.live="dateTo" 
                            class="w-full py-1.5 text-sm rounded-md border-gray-300 disabled:dark:bg-gray-400 dark:border-gray-700 dark:text-white dark:bg-gray-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <!-- Reset Filters Button -->
                    @if($search || $dateFrom !== '' || $dateTo !== '')
                        <button 
                            wire:click="clearFilters"
                            class="whitespace-nowrap text-sm sm:w-auto inline-flex items-center justify-center px-2 py-2 border border-red-300 dark:border-red-700 shadow-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('common.clear_filters') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                @if($users->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('attendance.no_qr_codes_found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.try_adjusting_filters') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-xl hover:border-indigo-300 dark:hover:border-indigo-500 hover:-translate-y-1">
                                <div class="p-6">
                                    <div class="flex justify-center mb-4">
                                        <img src="data:image/png;base64,{{ $this->generateQrCodeImage($user->id) }}" 
                                             alt="QR Code" 
                                             class="w-48 h-48 object-contain">
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $user->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                            {{ $user->email }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div class="mt-4 flex justify-center space-x-2">
                                        <button wire:click="generateQrCode({{ $user->id }})" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                                {{ __('attendance.view_large') }}
                                        </button>
                                        @if(auth()->user()->getCachedPermissions()->contains('download_qr_code'))
                                            <button wire:click="downloadQrCode({{ $user->id }})" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900 hover:bg-green-200 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                {{ __('attendance.download') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <x-modal wire:model="showQrModal" maxWidth="2xl">
        <div class="p-6">
            @if($currentQrCode)
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">
                        {{ $currentQrCode['user']->name }}'s QR Code
                    </h3>
                    <button wire:click="$set('showQrModal', false)" class="text-gray-400 hover:text-gray-500 dark:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <img src="data:image/png;base64,{{ $currentQrCode['qr_code'] }}" 
                         alt="QR Code" 
                         class="mx-auto w-64 h-64 object-contain">
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    @if(auth()->user()->getCachedPermissions()->contains('download_qr_code'))
                        <button type="button" 
                                wire:click="downloadQrCode({{ $currentQrCode['user']->id }})" 
                                class="inline-flex justify-center items-center px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            {{ __('attendance.download') }}
                        </button>
                    @endif
                    <button type="button" 
                            wire:click="closeModal" 
                            class="inline-flex justify-center px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition-colors duration-200">
                        {{ __('attendance.close') }}
                    </button>
                </div>
            @endif
        </div>
    </x-modal>

</div> 