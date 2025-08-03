<div class="w-full">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4">
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
           {{ $gym->name }}
        </h3>
        <a href="{{ route('gyms.index') }}" 
            class="w-full sm:w-auto text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
            {{ __('common.back_to_list') }}
        </a>
    </div>

    <div class="w-full mx-auto px-2 sm:px-6 lg:px-8 pb-10 h-full mt-5 bg-white shadow-xl rounded-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <nav class="-mb-px flex space-x-4 sm:space-x-8 min-w-max" aria-label="Tabs">
                    <button wire:click="setActiveTab('info')"
                        class="{{ $activeTab === 'info' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('gym.basic_info') }}
                    </button>
                    <button wire:click="setActiveTab('subscription')"
                        class="{{ $activeTab === 'subscription' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('package.gym_subscriptions') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mt-5 px-2 sm:px-0">
            @if ($activeTab === 'info')
                @livewire('gyms.view-gym-details', ['gymId' => $gymId])
            @elseif ($activeTab === 'subscription')
                @livewire('gyms.package-subscription-history', ['gymId' => $gymId])
            @endif
        </div>
    </div>
</div>
