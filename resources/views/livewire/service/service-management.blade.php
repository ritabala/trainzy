<div>
    <div>
        @hasCachedPermission('add_service')
            <button wire:click="addService()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('services.add') }}
            </button>
        @endhasCachedPermission
    </div>  
    @livewire('service.service-table')
</div> 
