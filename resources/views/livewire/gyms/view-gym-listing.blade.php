<div class="mx-auto py-10">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="{{ $listing->gym->logo_url }}" alt="{{ $listing->gym->name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->gym->name }}</h2>
                    <div class="flex items-center space-x-2 mt-2">
                        @if($listing->is_sponsored)
                            <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded text-xs">Sponsored</span>
                        @endif
                        @if($listing->badges)
                            @foreach(json_decode($listing->badges) as $badge)
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs capitalize">{{ str_replace('_', ' ', $badge) }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="editBasicInfo" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                    Edit Basic Info
                </button>
            </div>
        </div>

        <!-- Main Image -->
        <div class="mb-6">
            <img src="{{ $listing->main_image_url }}" alt="Main Image" class="w-full h-64 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
        </div>

        <!-- Image Gallery -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Gallery</h3>
                <div>
                    <button wire:click="addImages" class="px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">
                        Add Images
                    </button>
                </div>
            </div>
            
            @if($listing->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($listing->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image->image) }}" 
                                 alt="Gym Image" 
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer transition-transform duration-200 group-hover:scale-105">
                            @if(!$image->is_main)
                                <div class="absolute top-2 left-2">
                                    <button wire:click="setMainImage({{ $image->id }})" class="px-2 py-1 bg-blue-600 text-white text-xs rounded-lg">Set Main</button>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <button wire:click="deleteImage({{ $image->id }})" class="px-2 py-1 bg-red-600 text-white text-xs rounded-lg">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No images</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding some images to showcase your gym.</p>
                    <div class="mt-6">
                        <button wire:click="addImages" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Images
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Description</h3>
                <button wire:click="editDescription" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                    Edit
                </button>
            </div>
            <p class="text-gray-700 dark:text-gray-300">{{ $listing->about }}</p>
        </div>

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Facilities</h3>
                <button wire:click="editFacilities" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                    Edit
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                @forelse($listing->gymFacilities as $gymFacility)
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $gymFacility->facility->name)) }}</span>
                @empty
                    <span class="text-gray-500">No facilities listed.</span>
                @endforelse
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Operating Hours</h3>
                <button wire:click="editTimings" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                    Edit
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($listing->timings as $timing)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="font-medium text-gray-900 dark:text-gray-100 capitalize">{{ $timing->day }}</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            @if($timing->open_time && $timing->close_time)
                                {{ \Carbon\Carbon::parse($timing->open_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($timing->close_time)->format('g:i A') }}
                            @else
                                <span class="text-red-500">Closed</span>
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="col-span-2">
                        <span class="text-gray-500">No operating hours listed.</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Reviews -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reviews</h3>
                <div class="flex items-center gap-2">
                    @if($listing->reviews->count() > 0)
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $listing->reviews->avg('rating') ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ round($listing->reviews->avg('rating'), 1) }} ({{ $listing->reviews->count() }} reviews)
                        </span>
                    @endif
                   
                </div>
            </div>
            <livewire:gyms.review-management :listing-id="$listing->id" />
        </div>

        <div class="flex justify-end">
            <a href="{{ route('gym-listings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Back to Listings</a>
        </div>
    </div>

    <!-- Image Upload Modal -->
    @if($showImageModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeImageModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800" wire:click.stop>
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add Images to Gallery</h3>
                        <button wire:click="closeImageModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Images
                        </label>
                        <input type="file" wire:model="newImages" multiple accept="image/*" 
                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      dark:file:bg-blue-900 dark:file:text-blue-300">
                        @error('newImages.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($newImages)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Main Image
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($newImages as $index => $image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}" 
                                             alt="Preview" 
                                             class="w-full h-24 object-cover rounded-lg border-2 {{ $index == $mainImageIndex ? 'border-blue-500' : 'border-gray-300' }} cursor-pointer"
                                             wire:click="setMainImage({{ $index }})">
                                        @if($index == $mainImageIndex)
                                            <div class="absolute top-1 right-1">
                                                <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full">Main</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3">
                        <button wire:click="closeImageModal" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button wire:click="uploadImages" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50"
                                @if(!$newImages) disabled @endif>
                            Upload Images
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        </div>
    @endif
</div> 