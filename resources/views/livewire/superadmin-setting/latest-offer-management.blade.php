<div class="w-full mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Latest Offers</h1>
        <button wire:click="openCreate" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">+ Add Offer</button>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($offers as $offer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $offer->type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $offer->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($offer->expires_at)
                            {{ now()->diffInDays($offer->expires_at, false) > 0 ? now()->diffForHumans($offer->expires_at, ['parts' => 2, 'short' => true, 'syntax' => 1]) : 'Expired' }}
                        @else
                            --
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button wire:click="openEdit({{ $offer->id }})" class="text-blue-600 hover:underline mr-2">Edit</button>
                        <button wire:click="confirmDelete({{ $offer->id }})" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No offers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal for Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">&times;</button>
                <livewire:superadmin-setting.create-edit-latest-offer :promotionId="$editId" wire:key="modal-{{ $editId ?? 'new' }}" />
            </div>
        </div>
    @endif

    <!-- Delete Confirmation -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-center">
                <h2 class="text-lg font-bold mb-4">Delete Offer?</h2>
                <p class="mb-6 text-gray-600">Are you sure you want to delete this offer?</p>
                <div class="flex justify-center gap-4">
                    <button wire:click="deleteOffer" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">Delete</button>
                    <button wire:click="$set('confirmingDeleteId', null)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
