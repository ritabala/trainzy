<div class="w-full">
    <div class="p-8 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="space-y-8">
            <div class="flex items-center space-x-6">
                <div class="inline-block">
                    <img src="{{ $gym->logo_url }}" alt="{{ $gym->name }}" class="w-20 h-20 rounded-full object-cover">
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-200">{{ $gym->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('gym.basic_info') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 border border-gray-100 rounded-lg p-6 gap-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="space-y-6">
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.name')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->name }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.address')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->address ?? __('gym.not_specified') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.phone')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->phone ?? __('gym.not_specified') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.email')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->email ?? __('gym.not_specified') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.website')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">
                            @if($gym->website)
                                <a href="{{ $gym->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $gym->website }}
                                </a>
                            @else
                                {{__('gym.not_specified')}}
                            @endif
                        </dd>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.timezone')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->timezone ?? __('gym.not_specified') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.locale')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $gym->locale ?? 'en' }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{__('gym.currency')}}</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-200">
                            @if($gym->currency)
                                {{ $gym->currency->name }} ({{ $gym->currency->code }})
                            @else
                                {{ __('gym.not_specified') }}
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
