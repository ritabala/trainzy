<div>
    @if (session()->has('success'))
        <div>
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <div class="max-w-5xl mx-auto mt-6">
            <!-- Message Header -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ $message->subject }}</h1>
                            
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-300 font-medium text-lg">
                                            {{ substr($message->creator->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $message->creator->name }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $message->created_at->format('d M, Y H:i') }}
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <i class="fas fa-paper-plane mr-1.5"></i>
                                    {{ __('common.to') }}:
                                </span>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $this->getRecipientNames() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="p-4 min-h-[200px]">
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        {!! nl2br(e($message->body)) !!}
                    </div>
                </div>
            </div>

            <!-- Activity Class Info (if applicable) -->
            @if($message->activityClass)
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="p-4">
                    <div class="flex items-center space-x-2 text-sm">
                        <div class="w-8 h-8 rounded bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-indigo-600 dark:text-indigo-300"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300">{{ __('common.related_activity') }}: {{ $message->activityClass->name }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Message Footer -->
            <div class="bg-white dark:bg-gray-800">
                <div class="p-4">
                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock"></i>
                            <span>{{ __('common.created_at') }}: {{ $message->created_at->format('d M, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>