<div>
    <div>
        @hasCachedPermission('manage_settings')
            <button wire:click="addTax()"
                class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 max-sm:ml-6">
                {{ __('products.add_tax') }}
            </button>
        @endhasCachedPermission
    </div>  
    @livewire('settings.tax.tax-list')
</div> 