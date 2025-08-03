<div>
    @if($show)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-500 dark:bg-opacity-75 z-50" aria-hidden="true"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden bg-white dark:shadow-none dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('time_slots.schedule_for') }} {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                        </h2>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-4">
                            {{ $activityClassName }}
                        </p>

                        <div class="space-y-2 overflow-y-auto max-h-[500px] bg-gray-100 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-3">
                            @forelse($slots as $slot)
                                <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center gap-2">
                                        @if($slot['is_date_specific'])
                                            <div class="group relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div class="absolute bottom-full left-0 -translate-x-1/2 mb-2 hidden group-hover:block">
                                                    <div class="bg-gray-800 dark:bg-gray-700 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                                        {{ __('time_slots.date_specific') }}
                                                        <div class="absolute left-0 -translate-x-1/2 border-4 border-transparent border-t-gray-800 dark:border-t-gray-700"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="group relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <div class="absolute bottom-full left-0 -translate-x-1/2 mb-2 hidden group-hover:block">
                                                    <div class="bg-gray-800 dark:bg-gray-700 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                                        {{ __('time_slots.weekly') }}
                                                        <div class="absolute left-0 -translate-x-1/2 border-4 border-transparent border-t-gray-800 dark:border-t-gray-700"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($slot['start_time'])->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($slot['end_time'])->format('g:i A') }}
                                        </span>
                                    </div>
                                    @if(isset($slot['instructor']) && $slot['instructor'])
                                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                                            {{ $slot['instructor']['name'] }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    {{ __('time_slots.no_time_slots_for_date') }}
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6">
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="inline-flex w-full justify-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                        >
                            {{ __('common.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 