<div>
    <div>
        @hasCachedPermission('add_invoice')
            <button wire:click="addInvoice"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('finance.invoices.add') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('invoice.invoice-table')
</div> 
