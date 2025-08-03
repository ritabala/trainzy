<div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex-1">
            @hasCachedPermission('add_package')
                <button wire:click="addPackage" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6 dark:bg-blue-600 dark:hover:bg-blue-800">
                    {{ __('package.add') }}
                </button>
            @endhasCachedPermission
        </div>
    </div>
    @livewire('packages.packages-table')
</div> 