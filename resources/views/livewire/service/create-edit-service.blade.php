<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    <form wire:submit="save">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('services.name') }}</label>
                <input type="text" wire:model="name" id="name" placeholder="{{ __('activity.plc_name') }}"
                    class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Is Active -->
            <div class="flex items-center">
                <input type="checkbox" wire:model="is_active" id="is_active" 
                    class="rounded text-sm border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600">
                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">{{ __('common.active') }}</label>
                @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-600 dark:text-gray-300 dark:hover:bg-indigo-700 dark:focus:ring-indigo-500">
                    {{ $serviceId ? __('services.update') : __('services.create') }}
                </button>
            </div>
        </div>
    </form>
</div>
