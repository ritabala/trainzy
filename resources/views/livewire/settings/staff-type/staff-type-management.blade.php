<div>
    <div>
        @hasCachedPermission('manage_settings')
            <button wire:click="addStaffType()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('staff.add_staff_type') }}
            </button>
        @endhasCachedPermission
    </div>  
    @livewire('settings.staff-type.staff-type-list')
</div> 