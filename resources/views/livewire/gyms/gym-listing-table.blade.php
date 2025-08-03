<div>
<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
            {{ session('error') }}
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
                placeholder="Search Gym by Name" 
                autocomplete="off"
                class="w-full py-1.5 text-gray-700 dark:text-gray-300 dark:bg-gray-800 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300"
            >
        </div>
    </div>

    <!-- Table -->
    <div class="mt-6">
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">Gym</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">Main Image</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">Badges</th>
                                <th wire:click="sortBy('is_sponsored')" 
                                    class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider cursor-pointer">
                                    Sponsored
                                    @if($sortField === 'is_sponsored')
                                        <span class="ml-1">
                                            {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                                        </span>
                                    @endif
                                </th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($listings as $listing)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-xs sm:text-sm dark:text-gray-300">
                                    <td class="px-2 sm:px-4 py-3 sm:py-6">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 sm:h-10 sm:w-10">
                                                <img src="{{ $listing->gym->logo_url ?? asset('images/listing/1.jpeg') }}" alt="Gym Logo" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                            </div>
                                            <div class="ml-2 sm:ml-4">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ $listing->gym->name ?? '-' }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $listing->gym->email ?? '-' }}</div>
                                                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $listing->gym->phone ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        <img src="{{ $listing->main_image_url }}" alt="Main Image" class="w-12 h-12 sm:w-14 sm:h-14 rounded object-cover border border-gray-200 dark:border-gray-700">
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        @if($listing->badges)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(json_decode($listing->badges) as $badge)
                                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs capitalize">{{ str_replace('_', ' ', $badge) }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm">
                                        @if($listing->is_sponsored)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded text-xs">Yes</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 rounded text-xs">No</span>
                                        @endif
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-6 whitespace-nowrap text-xs sm:text-sm text-right font-medium">
                                        <div class="flex gap-2 sm:gap-1 justify-between items-center">
                                            <button onclick="window.location='{{ route('gym-listings.show', ['gym_listing' => $listing->id]) }}'" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 relative group" title="View">
                                                <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    View
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            <a href="{{ route('gym-listings.edit', $listing->id) }}" class="text-gray-600 dark:text-gray-300 hover:text-yellow-600 relative group" title="Edit">
                                                <span class="absolute bottom-full dark:bg-white dark:text-gray-900 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    Edit
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button wire:click="$dispatch('deleteListing', { listingId: {{ $listing->id }} })" class="text-gray-600 dark:text-gray-300 hover:text-red-600 relative group" title="Delete">
                                                <span class="absolute bottom-full left-1/2 dark:bg-white dark:text-gray-900 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    Delete
                                                </span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        No gym listings found.
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
            {{ $listings->links() }}
        </div>
    </div>
</div>
</div> 