<div>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800 dark:text-gray-300">
        <div class="sm:flex sm:items-start">
            <div class="mt-3 sm:mt-0 sm:text-left w-full">
                <div class="mt-4">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('body_metrics.name') }}</label>
                                    <input 
                                        type="text" 
                                        id="name"
                                        wire:model="name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                        placeholder="{{ __('body_metrics.enter_body_metric_name') }}"
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('body_metrics.slug') }}</label>
                                    <input 
                                        type="text" 
                                        id="slug"
                                        wire:model="slug"
                                        class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                        placeholder="{{ __('body_metrics.enter_slug') }}"
                                    >
                                    @error('slug')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('body_metrics.description') }}</label>
                                <textarea 
                                    id="description"
                                    wire:model="description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                    placeholder="{{ __('body_metrics.enter_description') }}"
                                ></textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('body_metrics.unit') }}</label>
                                    <input 
                                        type="text" 
                                        id="unit"
                                        wire:model="unit"
                                        class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                        placeholder="{{ __('body_metrics.enter_unit') }}"
                                    >
                                    @error('unit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="display_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('body_metrics.display_order') }}</label>
                                    <input 
                                        type="number" 
                                        id="display_order"
                                        wire:model="display_order"
                                        class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-gray-400"
                                        placeholder="{{ __('body_metrics.enter_display_order') }}"
                                    >
                                    @error('display_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model="is_active"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-200">{{ __('common.active') }}</span>
                                </label>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button 
                                type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
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