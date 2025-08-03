<div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex space-x-4">
            <button wire:click="addMessage" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                {{ __('emails.messages.add') }}
            </button>
        </div>
    </div>

    @livewire('messages.messages-table')
</div> 