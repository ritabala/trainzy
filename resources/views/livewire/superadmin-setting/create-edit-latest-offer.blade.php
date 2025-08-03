<form wire:submit.prevent="save" class="space-y-4">
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div>
        <label class="block text-sm font-medium text-gray-700">Type</label>
        <input type="text" wire:model.defer="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" wire:model.defer="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea wire:model.defer="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"></textarea>
        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div class="flex gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Expires At</label>
            <input type="datetime-local" wire:model.defer="expires_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            @error('expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Trainer Count</label>
            <input type="number" wire:model.defer="trainer_count" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            @error('trainer_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Savings</label>
            <input type="number" step="0.01" wire:model.defer="savings" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
            @error('savings') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="flex justify-end gap-2 mt-6">
        <button type="button" wire:click="$dispatch('closeModal')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Cancel</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">Save</button>
    </div>
</form>
