<div>
    <div>
        @hasCachedPermission('add_payment')
            <button wire:click="addPayment()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('finance.payments.create') }}
            </button>
        @endhasCachedPermission
    </div>    
    @livewire('payment.payment-table')
</div> 
