<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4">
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
           {{ $user->name }}
        </h3>
        <a href="{{ route('staff.index') }}" 
            class="w-full sm:w-auto text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm dark:bg-gray-700 dark:hover:bg-gray-600">
            {{ __('common.back_to_list') }}
        </a>
    </div>
    <div class="max-w-full mx-auto px-2 sm:px-6 lg:px-8 pb-6 sm:pb-10 h-full mt-3 sm:mt-5 bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-100 dark:border-gray-700">
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex flex-wrap sm:flex-nowrap gap-2 sm:gap-8 px-2 sm:px-6" aria-label="Tabs">
                <button wire:click="setActiveTab('personal')"
                    class="{{ $activeTab === 'personal' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('staff.details') }}
                </button>
                <button wire:click="setActiveTab('documents')"
                    class="{{ $activeTab === 'documents' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('members.documents') }}
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-3 sm:mt-5 px-2 sm:px-0">
            @if ($activeTab === 'personal')
                @livewire('staff.personal-details', ['user' => $user])
            @elseif ($activeTab === 'documents')
                @livewire('staff.document-management', ['user' => $user])
            @endif
        </div>
    </div>
</div> 