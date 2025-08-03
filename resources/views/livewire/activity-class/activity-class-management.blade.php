<div>
    <div>
        @hasCachedPermission('add_activity_class')
            <button wire:click="addActivityClass()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('activity.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('activity-class.activity-class-table')
</div> 