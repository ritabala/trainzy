<div>
    <div>
        @hasCachedPermission('add_gym')
            <button wire:click="addGym()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6 dark:bg-blue-600 dark:hover:bg-blue-800">
                {{ __('gym.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('gyms.gym-table')
</div> 