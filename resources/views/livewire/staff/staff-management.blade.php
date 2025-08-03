<div>
    <div>
        @hasCachedPermission('add_staff')
            <button wire:click="addStaff()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6 dark:bg-blue-600 dark:hover:bg-blue-800">
                {{ __('staff.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('staff.staff-table')
</div> 