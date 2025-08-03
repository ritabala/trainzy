<form wire:submit.prevent="save" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
        <select wire:model.defer="type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            <option value="">Select Type</option>
            <option value="flash_offer">Flash Offer</option>
            <option value="top_trainers">Top Trainers</option>
            <option value="corporate">Corporate</option>
        </select>
        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input type="text" wire:model.defer="title" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea wire:model.defer="description" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Expires In</label>
        <input type="text" wire:model.defer="expires_in" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. 2 days, 5 hours" />
        @error('expires_in') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Trainer Count</label>
        <input type="number" wire:model.defer="trainer_count" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
        @error('trainer_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Savings</label>
        <input type="text" wire:model.defer="savings" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Save 20%" />
        @error('savings') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Save</button>
    </div>
</form>
