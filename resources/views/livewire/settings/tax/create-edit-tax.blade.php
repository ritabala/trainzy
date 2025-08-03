<div>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
        <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-200" id="modal-title">
                    {{ $isEditing ? __('products.edit_tax') : __('products.add_tax') }}
                </h3>
                <div class="mt-4">
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.tax_name') }}</label>
                                <input 
                                    type="text" 
                                    id="tax_name"
                                    wire:model="tax_name"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('products.enter_tax_name') }}"
                                >
                                @error('tax_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tax_percent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('products.tax_percent') }}</label>
                                <input 
                                    type="number" 
                                    id="tax_percent"
                                    wire:model="tax_percent"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('products.enter_tax_percent') }}"
                                >
                                @error('tax_percent')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ $isEditing ? __('products.update_tax') : __('products.create_tax') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
