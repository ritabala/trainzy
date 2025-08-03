<div class="px-4 w-full mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Gym Guides & Tips Management</h2>

    <!-- Add New Guide Button -->
    <button wire:click="openCreate" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">+ Add New Guide</button>

    <!-- Guides Table -->
    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Link</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($guides as $guide)
                <tr>
                    <td class="px-4 py-2 font-semibold">{{ $guide->title }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ Str::limit($guide->description, 50) }}</td>
                    <td class="px-4 py-2">{!! $guide->icon ? $guide->icon : '<span class=\'text-gray-400\'>—</span>' !!}</td>
                    <td class="px-4 py-2 text-blue-600 text-sm">
                        @if($guide->link)
                            <a href="{{ $guide->link }}" target="_blank" class="underline">Link</a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <button wire:click="openEdit({{ $guide->id }})" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs font-semibold">Edit</button>
                        <button wire:click="confirmDelete({{ $guide->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs font-semibold">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">No guides found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal for Create/Edit -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-8 relative">
            <button wire:click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            <h3 class="text-xl font-bold mb-4">{{ $editId ? 'Edit Guide' : 'Add New Guide' }}</h3>
            <form wire:submit.prevent="saveGuide" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input type="text" wire:model.defer="title" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea wire:model.defer="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Icon (SVG or class)</label>
                    <input type="text" wire:model.defer="icon" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('icon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Link (optional)</label>
                    <input type="url" wire:model.defer="link" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">{{ $editId ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation -->
    @if($confirmingDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 text-center">
            <h3 class="text-lg font-bold mb-4">Delete Guide?</h3>
            <p class="mb-6 text-gray-600">Are you sure you want to delete this guide? This action cannot be undone.</p>
            <div class="flex justify-center gap-4">
                <button wire:click="deleteGuide" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">Yes, Delete</button>
                <button wire:click="$set('confirmingDeleteId', null)" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
