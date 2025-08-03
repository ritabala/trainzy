<div class="w-full">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4">
        <h3 class="text-xl font-bold text-gray-800 leading-tight dark:text-gray-200">
           {{ $user->name }}
        </h3>
        <a href="{{ route('members.index') }}" 
            class="w-full sm:w-auto text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
            {{ __('members.back_to_members') }}
        </a>
    </div>

    <div class="w-full mx-auto px-2 sm:px-6 lg:px-8 pb-10 h-full mt-5 bg-white shadow-xl rounded-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <nav class="-mb-px flex space-x-4 sm:space-x-8 min-w-max" aria-label="Tabs">
                    <button wire:click="setActiveTab('personal')"
                        class="{{ $activeTab === 'personal' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('members.personal_details') }}
                    </button>
                    <button wire:click="setActiveTab('membership')"
                        class="{{ $activeTab === 'membership' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('membership.details') }}
                    </button>
                    <button wire:click="setActiveTab('activities')"
                        class="{{ $activeTab === 'activities' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('members.activities') }}
                    </button>
                    @if(has_module_access('body_metrics'))
                        <button wire:click="setActiveTab('measurements')"
                            class="{{ $activeTab === 'measurements' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            {{ __('members.body_measurements') }}
                        </button>
                    @endif
                    <button wire:click="setActiveTab('documents')"
                        class="{{ $activeTab === 'documents' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-200 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('members.documents') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mt-5 px-2 sm:px-0">
            @if ($activeTab === 'personal')
                @livewire('member.personal-details', ['user' => $user])
            @elseif ($activeTab === 'membership')
                @livewire('member.membership-details', ['user' => $user])
            @elseif ($activeTab === 'activities')
                @livewire('member.activity-classes', ['user' => $user])
            @elseif ($activeTab === 'measurements')
                @livewire('member.body-measurements', ['user' => $user])
            @elseif ($activeTab === 'documents')
                @livewire('member.document-management', ['user' => $user])
            @endif
        </div>
    </div>
</div>
