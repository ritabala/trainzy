<div class=" mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
            {{ $isEditing ? 'Edit Gym Listing' : 'Create Gym Listing' }}
        </h2>

        <form wire:submit.prevent="saveListing" class="space-y-6">
            <!-- Gym Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gym</label>
                <select wire:model="gym" class="w-full p-3 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    <option value="">Select a gym...</option>
                    @foreach($gyms as $gym)
                        <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                    @endforeach
                </select>
                @error('gym') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea wire:model="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" placeholder="Enter gym description..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <!-- Sponsored -->
            <div class="flex items-center">
                <input type="checkbox" wire:model="is_sponsored" id="is_sponsored" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <label for="is_sponsored" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Sponsored Listing</label>
            </div>

            <!-- Badges -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Badges</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($badges as $badge)
                        <label class="flex items-center" wire:key='badge-{{ $badge . rand(1, 1000000) }}'>
                            <input type="checkbox" wire:model="selectedBadges" value="{{ $badge }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $badge) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Facilities -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Facilities</label>
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                    @foreach($facilities as $facility)
                        <label class="flex items-center" wire:key='facility-{{ $facility->id . rand(1, 1000000) }}'>
                            <input type="checkbox" wire:model="selectedFacilities" value="{{ $facility->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst(Str::replace('_', ' ', $facility->name)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Timings -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Operating Hours</label>
                <div class="space-y-4">
                    @foreach($days as $day)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3 capitalize">{{ $day }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Opening Time</label>
                                    <input type="time" 
                                           wire:model="timings.{{ $day }}.open_time" 
                                           class="w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm">
                                    @error("timings.{$day}.open_time") 
                                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Closing Time</label>
                                    <input type="time" 
                                           wire:model="timings.{{ $day }}.close_time" 
                                           class="w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm">
                                    @error("timings.{$day}.close_time") 
                                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('gym-listings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                    {{ $isEditing ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div> 