<div>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
        <div class="sm:flex sm:items-start">
            <div class="mt-3 sm:mt-0 sm:text-left w-full">
                <div class="mt-4">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.name') }}</label>
                                <input 
                                    type="text" 
                                    id="name"
                                    wire:model="name"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('products.enter_product_name') }}"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="product_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.product_code') }}</label>
                                <input 
                                    type="text" 
                                    id="product_code"
                                    wire:model="product_code"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('products.enter_product_code') }}"
                                >
                                @error('product_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.description') }}</label>
                            <textarea 
                                id="description"
                                wire:model="description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                placeholder="{{ __('products.enter_product_description') }}"
                            ></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.price') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="price"
                                        step="0.01"
                                        wire:model="price"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                        placeholder="{{ __('products.enter_product_price') }}"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.quantity') }}</label>
                                <input 
                                    type="number" 
                                    id="quantity"
                                    wire:model="quantity"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('products.enter_product_quantity') }}"
                                >
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.expiry_date') }}</label>
                            <input 
                                type="date" 
                                id="expiry_date"
                                wire:model="expiry_date"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                            >
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.taxes') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($taxes as $tax)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="tax_{{ $tax->id }}"
                                            wire:model="selectedTaxes" 
                                            value="{{ $tax->id }}"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded text-sm placeholder-gray-400"
                                        >
                                        <label for="tax_{{ $tax->id }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            {{ $tax->tax_name }} ({{ $tax->tax_percent }}%)
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedTaxes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ $isEditing ? __('common.update') : __('common.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 