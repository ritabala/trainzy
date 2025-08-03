<div>
    <div>
        @hasCachedPermission('add_member')
            <button wire:click="addMember()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('members.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('member.member-table')
</div> 
