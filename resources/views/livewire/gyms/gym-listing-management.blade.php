<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Gym Listing Management</h2>
            <a href="{{ route('gym-listings.create', ['gym' => $selectedGymId]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-800">
                Add Listing
            </a>
    </div>

    @livewire('gyms.gym-listing-table')
</div> 