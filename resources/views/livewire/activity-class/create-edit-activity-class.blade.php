<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __('activity.name') }}
                </label>
                <input type="text" wire:model="name" id="name" placeholder="{{ __('activity.plc_name') }}"
                    class="mt-1 block text-sm w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __('activity.description') }}
                </label>
                <textarea wire:model="description" id="description" rows="3" placeholder="{{ __('activity.plc_description') }}"
                    class="mt-1 block text-sm w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Duration -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __('activity.duration') }}
                </label>
                <input type="number" wire:model="duration" id="duration" min="1" placeholder="{{ __('activity.plc_duration') }}"
                    class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:text-white dark:hover:bg-indigo-600 dark:focus:ring-indigo-500">
                    {{ $activityClassId ? __('activity.update') : __('activity.create') }}
                </button>
            </div>
        </div>
    </form>
</div> 