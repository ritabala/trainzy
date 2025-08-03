<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center pb-7 border-b border-gray-200 dark:border-gray-800 gap-4">
        <!-- Search -->
        <div class="w-full sm:w-64 mr-4 sm:mb-0 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="{{ __('staff.search_staff') }}" 
                autocomplete="off"
                class="w-full py-1.5 text-gray-700 dark:text-gray-300 dark:bg-gray-800 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
            >
        </div>

        <!-- Staff Type Filter -->
        <div class="w-full mr-4 sm:w-64">
            <x-dropdown align="left" width="48" :selectedValue="$this->staffType">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
                        <span>
                            {{ $this->staffType == '' ? __('staff.select_type') : $this->selectedStaffTypeName }}
                        </span>
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="py-1 dark:border dark:border-gray-700">
                        <x-dropdown-link :selected="$this->staffType == ''" wire:click="$set('staffType', '')">
                            {{ __('staff.select_type') }}
                        </x-dropdown-link>
                        @foreach($staffTypes as $type)
                            <x-dropdown-link :selected="$this->staffType == $type->id" wire:click="$set('staffType', '{{ $type->id }}')">
                                {{ $type->name }}
                            </x-dropdown-link>
                        @endforeach     
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Active Filter -->
        <div class="w-full mr-4 sm:w-64">
            <x-dropdown align="left" width="48" :selectedValue="$isActive">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 dark:text-gray-300 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300">
                        <span>
                            {{ $isActive === '' ? __('common.all_status') : ($isActive === '1' ? __('common.active') : __('common.inactive')) }}
                        </span>
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="py-1 dark:border dark:border-gray-700">
                        <x-dropdown-link :selected="$isActive === ''" wire:click="$set('isActive', '')">
                            {{ __('common.all_status') }}
                        </x-dropdown-link>
                        <x-dropdown-link :selected="$isActive === '1'" wire:click="$set('isActive', '1')">
                            {{ __('common.active') }}
                        </x-dropdown-link>
                        <x-dropdown-link :selected="$isActive === '0'" wire:click="$set('isActive', '0')">
                            {{ __('common.inactive') }}
                        </x-dropdown-link>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
        
        <!-- Reset Filters -->
        @if($this->isActive !== '' || $this->staffType || $this->search)
        <div class="w-full mr-4 sm:w-48">
            <button 
                wire:click="resetFilters"
                class="inline-flex items-center px-3 py-1 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 border border-red-300 dark:border-red-100 transition-colors duration-200"
            >
                {{ __('common.clear_filters') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="mt-6">
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('staff.staff_info') }}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider hidden sm:table-cell">{{__('staff.staff_type')}}</th>
                                <th wire:click="sortBy('created_at')" 
                                    class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer hidden sm:table-cell">
                                    {{__('staff.join_date')}}
                                    @if($sortField === 'joining_date')
                                        <span class="ml-1">
                                            {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                        </span>
                                    @endif
                                </th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.role')}}</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.status')}}</th>
                                @if(auth()->user()->getCachedPermissions()->contains('edit_staff') || 
                                    auth()->user()->getCachedPermissions()->contains('delete_staff') || 
                                    auth()->user()->getCachedPermissions()->contains('view_staff') ||
                                    $staff->contains('id', auth()->user()->id))
                                    <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{__('common.actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($staff as $staffMember)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-xs sm:text-sm dark:text-gray-300">
                                    <td class="px-2 sm:px-4 py-3 sm:py-6">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 sm:h-12 sm:w-12 flex-shrink-0">
                                                @if($staffMember->profile_photo_path && Storage::disk('public')->exists($staffMember->profile_photo_path))
                                                    <img src="{{ Storage::url($staffMember->profile_photo_path) }}" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                                @elseif($staffMember->gender)
                                                    <img src="{{ asset('images/' . $staffMember->gender . '.svg') }}" alt="Profile" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                                @else
                                                    <div class="h-8 w-8 sm:h-12 sm:w-12 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xs sm:text-sm font-bold border border-gray-200 dark:border-gray-600">
                                                        {{ strtoupper(substr($staffMember->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-2 sm:ml-4">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ $staffMember->name }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $staffMember->email }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $staffMember->phone_number }}</div>
                                                <div class="sm:hidden mt-1">
                                                    <span class="text-xs text-gray-500">{{ $staffTypes->where('id', $staffMember->staffDetail?->staff_type_id)->first()?->name ?? '-' }}</span>
                                                    <span class="text-xs text-gray-500 ml-2">{{ $staffMember->staffDetail?->date_of_joining?->format('Y-m-d') ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm hidden sm:table-cell">
                                        {{ $staffTypes->where('id', $staffMember->staffDetail?->staff_type_id)->first()?->name ?? '-' }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm hidden sm:table-cell">
                                        {{ $staffMember->staffDetail?->date_of_joining?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm hidden sm:table-cell">
                                        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-bold rounded-full {{ $staffMember->role[0]->name == 'admin-' . gym()->id ? 'text-red-800' : 'text-blue-800' }}">
                                            {{ $staffMember->role[0]->display_name ?? $staffMember->role[0]->name }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-bold rounded-full {{ $staffMember->is_active == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $staffMember->is_active == '1' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->getCachedPermissions()->contains('view_staff') || 
                                        auth()->user()->getCachedPermissions()->contains('edit_staff') || 
                                        auth()->user()->getCachedPermissions()->contains('delete_staff') || 
                                        auth()->user()->getCachedPermissions()->contains('change_password') ||
                                        auth()->user()->id === $staffMember->id)
                                        <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm text-right font-medium">
                                            <div class="flex gap-2 sm:gap-1 justify-between items-center">
                                                @hasCachedPermission('view_staff')
                                                    <a href="{{ route('staff.show', $staffMember->id) }}" 
                                                        class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group"
                                                        aria-label="{{ __('common.view') }}">
                                                        <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('common.view') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                @endhasCachedPermission
                                                @if(auth()->user()->getCachedPermissions()->contains('edit_staff') || auth()->user()->id === $staffMember->id)
                                                    <a href="{{ route('staff.edit', $staffMember->id) }}" 
                                                        class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group"
                                                        aria-label="{{ __('common.edit') }}">
                                                        <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('common.edit') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->getCachedPermissions()->contains('change_password') || auth()->user()->id === $staffMember->id)
                                                    <button wire:click="openPasswordModal({{$staffMember->id}})"
                                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 relative group"
                                                        aria-label="{{ __('staff.change_pwd') }}">
                                                        <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('staff.change_pwd') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if(auth()->user()->getCachedPermissions()->contains('delete_staff') && auth()->user()->id !== $staffMember->id)
                                                    <button wire:click="handleDeleteStaff({{$staffMember->id}})"
                                                        class="text-gray-600 dark:text-gray-300 hover:text-red-600 relative group"
                                                        aria-label="{{ __('common.delete') }}">
                                                        <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                            {{ __('common.delete') }}
                                                        </span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('staff.no_staff') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $staff->links() }}
        </div>
    </div>

    <!-- Password Change Modal -->
    <x-dialog-modal wire:model.live="showPasswordModal">
        <x-slot name="title">
            {{ __('staff.change_pwd') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="updatePassword" id="upload-form">
                <div x-data="{ showPassword: false, showConfirm: false }">
                    <div class="mt-4">   
                        <!-- Password field with toggle -->
                        <div class="mb-4" x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('staff.new_pwd') }}</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" wire:model="password"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-md">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="showPassword = !showPassword">
                                    <template x-if="!showPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7" />
                                        </svg>
                                    </template>
                                    <template x-if="showPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7M3 3l18 18" />
                                        </svg>
                                    </template>
                                </div>
                            </div>
                            @error('password') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('staff.confirm_pwd') }}</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" wire:model="password_confirmation"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-md">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="showConfirm = !showConfirm">
                                <template x-if="!showConfirm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7" />
                                    </svg>
                                </template>
                                <template x-if="showConfirm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7M3 3l18 18" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                        @error('password_confirmation') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <button type="submit" form="upload-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700  focus:ring-indigo-500  sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('members.update') }}
            </button>
            <button type="button" wire:click="closePasswordModal" class="mt-3 inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('common.cancel') }}
            </button>
        </x-slot>
    </x-dialog-modal>
</div> 