<div>
    <div>
        @hasCachedPermission('add_member_attendance')
            <button wire:click="addMemberAttend()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('members.attendance.create_attend') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('attendance.members.members-attend-table')
</div> 
