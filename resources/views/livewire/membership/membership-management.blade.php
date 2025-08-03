<div>
    <div>
        @hasCachedPermission('add_membership')
            <button wire:click="addMembership()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('membership.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('membership.membership-table')
</div> 
