<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-400 dark:text-green-300">
        {{ session('message') }}
    </div>
    @endif 

    <div class="flex flex-col sm:flex-row justify-start gap-4 items-center pb-7 border-b border-gray-200 dark:border-gray-700">
        

        <!-- Date Filter -->
        <div class="relative w-full sm:w-64 max-sm:mt-4 max-md:mt-2 max-xl:mt-2 mt-2">
            <div class="absolute -top-4 left-0 text-xs text-gray-500 dark:text-gray-300">{{ __('members.attendance.check_in_date') }}</div>
            <input 
                type="date" 
                wire:model.live="filterDate" 
                class="w-full py-1.5 text-sm rounded-md border-gray-300 disabled:dark:bg-gray-400 dark:border-gray-700 dark:text-white dark:bg-gray-800 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer transition-all duration-200 hover:border-indigo-300"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

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
                placeholder={{ __('members.attendance.search_placeholder') }} 
                class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-400 dark:border-gray-700 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
            >
        </div>

        <!-- Reset Filters Button -->
        @if($this->hasActiveFilters)
        <button 
            wire:click="clearFilters"
            class="whitespace-nowrap mt-2 text-sm sm:w-auto inline-flex items-center justify-center px-2 py-2 border border-red-300 dark:border-red-700 shadow-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200"
        >
            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ __('common.clear_filters') }}
        </button>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th wire:click="sortBy('user_id')" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer">
                        {{ __('members.attendance.name') }}
                        @if($sortField === 'user_id')
                            <span class="ml-1">
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            </span>
                        @endif
                    </th>
                    <th wire:click="sortBy('check_in_at')" class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer">
                        {{ __('members.attendance.check_in') }}
                        @if($sortField === 'check_in_at')
                            <span class="ml-1">
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            </span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                        {{ __('common.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($attendances as $attendance)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm dark:text-gray-300">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="h-8 w-8 sm:h-12 sm:w-12 flex-shrink-0">
                                    @if($attendance->user->profile_photo_path && Storage::disk('public')->exists($attendance->user->profile_photo_path))
                                        <img src="{{ Storage::url($attendance->user->profile_photo_path) }}" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                    @elseif($attendance->user->gender)
                                        <img src="{{ asset('images/' . $attendance->user->gender . '.svg') }}" alt="Profile" class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="h-8 w-8 sm:h-12 sm:w-12 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xs sm:text-sm font-bold border border-gray-200 dark:border-gray-600">
                                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <a href="{{ route('attendance.staff.show', $attendance->id) }}" class="group block">
                                        <div class="relative group" x-data="{ isTruncated: false }" x-init="isTruncated = $el.querySelector('.truncate').scrollWidth > $el.querySelector('.truncate').clientWidth">
                                            <div class="truncate text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $attendance->user->name }}
                                            </div>
                                            <template x-if="isTruncated">
                                                <div class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                                    {{ $attendance->user->name }}
                                                    <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $attendance->user->email }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $attendance->check_in_at?->format('Y-m-d g:i A') ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                @hasCachedPermission('view_staff_attendance')
                                <a href="{{ route('attendance.staff.show', $attendance->id) }}"
                                    class="text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 relative group"
                                    aria-label="{{ __('common.view') }}">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('common.view') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @endhasCachedPermission
                                @hasCachedPermission('edit_staff_attendance')
                                <button wire:click="edit({{ $attendance->id }})"
                                    class="text-gray-600 hover:text-yellow-600 dark:text-gray-300 dark:hover:text-yellow-400 relative group"
                                    aria-label="{{ __('common.edit') }}">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('common.edit') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @endhasCachedPermission
                                @hasCachedPermission('delete_staff_attendance')
                                <button wire:click="handleDeleteStaffAttend({{ $attendance->id }})"
                                    class="text-gray-600 hover:text-red-600 dark:text-gray-300 dark:hover:text-red-400 relative group"
                                    aria-label="{{ __('common.delete') }}">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('common.delete') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @endhasCachedPermission
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                            {{ __('members.attendance.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div> 