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
                placeholder="{{ __('common.search') }}" 
                class="w-full py-1.5 text-gray-700 placeholder:text-gray-500 text-sm pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 hover:border-indigo-300 dark:text-white dark:placeholder:text-gray-400 dark:bg-gray-800 dark:border-gray-700"
            >
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg mt-6 shadow dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark:text-white dark:bg-gray-800 dark:border-gray-700 dark:border">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider"> 
                        {{ __('emails.messages.subject') }}
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider"> 
                        {{ __('emails.messages.recipient') }}
                    </th>
                    <th class="px-6 py-3 cursor-pointer text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider" wire:click="sortBy('created_at')" > 
                        <div class="flex items-center space-x-1">
                            <span>{{ __('common.created_at') }}</span>
                            @if ($sortField === 'created_at')
                                <span class="text-gray-400">
                                    @if ($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </th>
                    @if(auth()->user()->getCachedPermissions()->contains('delete_message'))
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-500 dark:text-gray-400 tracking-wider">
                            {{ __('common.actions') }}
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($messages as $message)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-sm dark:text-gray-300">
                        <td class="px-4 py-3">
                            <div class="flex-wrap flex max-w-[200px] ">
                                <a href="{{ route('messages.show', $message->id) }}" class="text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors duration-200 hover:underline decoration-indigo-600 dark:decoration-indigo-400">
                                    {{ $message->subject }}
                                </a>
                            </div>
                                
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="relative group lg:max-w-[200px]" x-data="{ isTruncated: false }" x-init="isTruncated = $el.querySelector('.names').scrollWidth > $el.querySelector('.names').clientWidth">
                                <div class="flex items-center">
                                    @if($message->recipient_type === 'staff')
                                        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 mr-1 flex-shrink-0">{{ __('emails.messages.recipient_type.staff') }}:</span>
                                    @else
                                        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1 flex-shrink-0">{{ __('emails.messages.recipient_type.members') }}:</span>
                                    @endif
                                    <div class="names truncate">
                                        {{ $this->getRecipientNames($message) }}
                                    </div>
                                </div>
                                <template x-if="isTruncated">
                                    <div class="absolute bottom-full left-0 px-2 py-1 text-xs text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 mb-1">
                                        {{ $this->getRecipientNames($message) }}
                                        <div class="absolute -bottom-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $message->created_at->format('Y-m-d') }}</td>
                        @if(auth()->user()->getCachedPermissions()->contains('view_messages') || auth()->user()->getCachedPermissions()->contains('delete_message'))
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    @hasCachedPermission('view_messages')
                                    <a href="{{ route('messages.show', $message->id) }}" 
                                        class="text-gray-600 hover:text-yellow-600 dark:text-gray-300 dark:hover:text-yellow-400 relative group"
                                        aria-label="{{ __('common.view') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('common.view') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button wire:click="resendMessage({{ $message->id }})"
                                        class="text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 relative group"
                                        aria-label="{{ __('emails.messages.resend') }}">
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ __('emails.messages.resend') }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    @endhasCachedPermission
                                    @hasCachedPermission('delete_message')
                                    <button wire:click="handleDeleteMessage({{ $message->id }})"
                                        class="text-gray-600 hover:text-red-600 dark:text-gray-300 dark:hover:text-red-400 relative group"
                                        aria-label="{{ __('common.delete') }}">
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
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                            {{ __('emails.messages.no_messages') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div> 