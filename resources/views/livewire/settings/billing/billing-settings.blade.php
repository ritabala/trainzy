<div class="w-full">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md mb-4 dark:bg-green-900 dark:border-green-400 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    <div class="w-full mx-auto px-2 sm:px-6 lg:px-8 pb-10 h-full mt-5 bg-white shadow-xl rounded-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <nav class="-mb-px flex space-x-4 sm:space-x-8 min-w-max" aria-label="Tabs">
                    <button wire:click="setActiveTab('plan_details')"
                        class="{{ $activeTab === 'plan_details' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('settings.billing.plan_details') }}
                    </button>
                    <button wire:click="setActiveTab('purchase_history')"
                        class="{{ $activeTab === 'purchase_history' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('settings.billing.purchase_history') }}
                    </button>
                    <button wire:click="setActiveTab('offline_requests')"
                        class="{{ $activeTab === 'offline_requests' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('settings.billing.offline_requests') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mt-5 px-2 sm:px-0">
            @if ($activeTab === 'plan_details')
                @livewire('settings.billing.plan-details')
            @elseif ($activeTab === 'purchase_history')
                @livewire('settings.billing.purchase-history')
            @elseif ($activeTab === 'offline_requests')
                @livewire('settings.billing.offline-requests')
            @endif
        </div>
    </div>
</div> 