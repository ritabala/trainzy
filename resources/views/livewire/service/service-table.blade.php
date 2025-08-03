<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-400 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-center pb-7 border-b border-gray-200 dark:border-gray-700">
        <!-- Search -->
        <div class="w-full sm:w-64 mb-4 sm:mb-0 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="{{ __('services.search_services') }}" 
                class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
            >
        </div>

        <!-- Active Filter -->
        <div class="w-full sm:w-48">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex justify-between w-full items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-500 bg-white hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:bg-gray-800 dark:border-gray-700">
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
                        <x-dropdown-link wire:click="$set('isActive', '')" :selected="$isActive === ''">
                            {{ __('common.all_status') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="$set('isActive', '1')" :selected="$isActive === '1'">
                            {{ __('common.active') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="$set('isActive', '0')" :selected="$isActive === '0'">
                            {{ __('common.inactive') }}
                        </x-dropdown-link>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @foreach(['name' => __('common.name'), 'is_active' => __('common.status'), 'created_at' => __('common.created_at')] as $key => $label)
                        <th wire:click="sortBy('{{ $key }}')" 
                            class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider {{ !in_array($key, $nonSortableFields) ? 'cursor-pointer' : '' }}">
                            {{ $label }}
                            @if($sortField === $key)
                                <span class="ml-1">
                                    {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                </span>
                            @endif
                        </th>
                    @endforeach
                    @if(auth()->user()->getCachedPermissions()->contains('edit_service') || auth()->user()->getCachedPermissions()->contains('delete_service'))
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">{{ __('common.actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm dark:text-gray-300">
                        <td class="px-4 py-3">
                            <div class="relative group" x-data="{ isTruncated: false }" x-init="isTruncated = $el.querySelector('.truncate').scrollWidth > $el.querySelector('.truncate').clientWidth">
                                <div class="truncate ">
                                    {{ $service->name }}
                                </div>
                                <template x-if="isTruncated">
                                    <div class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                        {{ $service->name }}
                                        <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $service->is_active ? __('common.active') : __('common.inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $service->created_at?->format('Y-m-d') }}</td>
                        @if(auth()->user()->getCachedPermissions()->contains('edit_service') || auth()->user()->getCachedPermissions()->contains('delete_service'))
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                @hasCachedPermission('edit_service')
                                <a href="{{ route('services.edit', $service) }}" 
                                   class="text-gray-600 hover:text-yellow-600 dark:text-gray-300 dark:hover:text-yellow-400 relative group"
                                   aria-label="{{ __('services.edit') }}">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('common.edit') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endhasCachedPermission
                                @hasCachedPermission('delete_service')
                                <button wire:click="handleDeleteService({{$service->id}})"
                                    class="text-gray-600 hover:text-red-600 dark:text-gray-300 dark:hover:text-red-400 relative group"
                                    aria-label="{{ __('services.delete') }}">
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                        {{ __('common.delete') }}
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endhasCachedPermission
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                            {{ __('services.no_services_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
