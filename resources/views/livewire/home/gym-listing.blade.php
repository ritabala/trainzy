<div class="min-h-screen bg-white">
    <!-- Minimal Hero Section -->
    <section class="relative bg-white border-b border-gray-100">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/listing/1.jpeg') }}');">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="text-center max-w-3xl mx-auto">
                <!-- Simple Badge -->
                <div class="inline-flex items-center gap-2 text-white/90 text-sm font-medium mb-6">
                    <svg class="h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Trusted by 10,000+ fitness enthusiasts</span>
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Find your perfect
                    <span class="text-blue-300">gym</span>
                </h1>
                
                <p class="text-lg text-white/90 mb-10 max-w-2xl mx-auto">
                    Discover and compare gyms in your area with transparent pricing, real reviews, and instant booking.
                </p>

                <!-- Clean Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-2 flex flex-col sm:flex-row gap-2">
                        <!-- Location Input -->
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="location"
                                type="text" 
                                placeholder="Location" 
                                class="w-full pl-12 pr-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-xl text-sm"
                            >
                        </div>

                        <!-- Search Input -->
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text" 
                                placeholder="Gym name or type" 
                                class="w-full pl-12 pr-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-xl text-sm"
                            >
                        </div>

                        <!-- Search Button -->
                        <button class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200 font-medium text-sm">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Simple Stats -->
                <div class="flex justify-center items-center gap-8 mt-8 text-white/80 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>4.8/5 average rating</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Free trial available</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Filters Toggle -->
    <div class="bg-gray-50 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <button 
                wire:click="toggleFilters"
                wire:loading.class="opacity-50"
                wire:loading.attr="disabled"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors duration-150 text-sm"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                </svg>
                <span wire:loading.remove>{{ $showFilters ? 'Hide filters' : 'Show filters' }}</span>
                <span wire:loading>Loading...</span>
            </button>
        </div>
    </div>

    <!-- Clean Filters Section -->
    @if($showFilters)
    <section class="bg-white border-b border-gray-100 sticky top-0 z-40 transition-all duration-200 ease-out" data-filters-section wire:loading.class="opacity-75">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
               
                <!-- Facilities -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Facilities</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($facilities as $facility)
                                @php($isSelected = in_array($facility->id, $selectedFacilities))
                                <label class="relative flex items-center p-3 rounded-lg cursor-pointer transition-all duration-150 hover:bg-white {{ $isSelected ? 'bg-blue-50 border border-blue-200' : 'hover:shadow-sm' }}">
                                    <input 
                                        wire:model.live.debounce.300ms="selectedFacilities" 
                                        type="checkbox" 
                                        value="{{ $facility->id }}"
                                        class="sr-only"
                                    >
                                    <div class="flex items-center gap-3 w-full">
                                        <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded border-2 transition-colors duration-150 {{ $isSelected ? 'bg-blue-600 border-blue-600' : 'border-gray-300 bg-white' }}">
                                            @if($isSelected)
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            @endif
                                        </div>
                                        <span class="text-lg">{{ $facility->icon }}</span>
                                        <span class="text-sm text-gray-700 font-medium">{{ ucfirst(str_replace('_', ' ', $facility->name)) }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Minimum Rating</label>
                    <select wire:model.live.debounce.300ms="rating" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="0">Any rating</option>
                        <option value="2">2+ stars</option>
                        <option value="3">3+ stars</option>
                        <option value="4">4+ stars</option>
                        <option value="4.5">4.5+ stars</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            <div class="mt-6 flex justify-between items-center">
                <button 
                    wire:click="resetFilters"
                    wire:loading.class="opacity-50"
                    wire:loading.attr="disabled"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors duration-150"
                >
                    <span wire:loading.remove>Reset filters</span>
                    <span wire:loading>Resetting...</span>
                </button>
                <span class="text-sm text-gray-500">
                    <span wire:loading.remove>{{ $totalGyms }} gyms found</span>
                    <span wire:loading>Loading...</span>
                </span>
            </div>
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Clean Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Gyms near you</h2>
                        <p class="text-gray-600 text-sm mt-1">Find the perfect gym for your fitness goals</p>
                    </div>
                </div>

                <!-- Gym Cards Grid -->
                @if($viewMode === 'list')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-50">
                    <!-- Loading Indicator -->
                    <div wire:loading class="col-span-full flex justify-center py-8">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading gyms...</span>
                        </div>
                    </div>
                    
                    @forelse($gyms as $gym)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200 group" data-gym-card>
                        <!-- Gym Image -->
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if($gym['logo'])
                            <img src="{{ $gym['logo'] }}" alt="{{ $gym['name'] }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            @endif
                            
                            <!-- Sponsored Badge -->
                            @if(isset($gym['sponsored']) && $gym['sponsored'])
                            <div class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                Sponsored
                            </div>
                            @endif
                        </div>

                        <!-- Gym Info -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $gym['name'] }}</h3>
                                <p class="text-sm text-gray-600 flex items-center gap-1 mb-3">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $gym['address'] }}
                                </p>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $gym['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">{{ round($gym['rating'], 1) }} ({{ $gym['reviews'] }})</span>
                            </div>

                            <!-- Price -->
                            <div class="mb-4">
                                <span class="text-2xl font-bold text-gray-900">${{ $gym['price'] }}</span>
                                <span class="text-sm text-gray-500">/month</span>
                            </div>

                            <!-- Facilities Preview -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach(array_slice($gym['facilities'], 0, 3) as $facility)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $facilities[$facility] ?? $facility)) }}
                                </span>
                                @endforeach
                                @if(count($gym['facilities']) > 3)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">+{{ count($gym['facilities']) - 3 }}</span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('home.gym-details', $gym['slug']) }}"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center block"
                            >
                                View details
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No gyms found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Clean Pagination -->
                @if($gyms->hasPages())
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        <span wire:loading.remove>
                            Showing {{ $gyms->firstItem() ?? 0 }} to {{ $gyms->lastItem() ?? 0 }} of {{ $gyms->total() }} results
                        </span>
                        <span wire:loading>Loading results...</span>
                    </div>
                    
                    <div wire:loading.class="opacity-50">
                        {{ $gyms->links() }}
                    </div>
                </div>
                @endif
                @else
                <!-- Map View Placeholder -->
                <div class="bg-gray-100 rounded-xl h-96 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Map View</h3>
                        <p class="mt-1 text-sm text-gray-500">Interactive map view coming soon.</p>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Minimal Sidebar -->
            @if(count($sidebarPromotions) > 0)
            <div class="lg:w-80 flex-shrink-0">
                <div class="sticky top-8 space-y-6">
                    @foreach($sidebarPromotions as $promotion)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $promotion->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $promotion->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Minimal Featured Offers -->
    @if(count($featuredOffers) > 0)
    <section class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Special offers</h2>
                <p class="text-gray-600">Limited-time deals from top gyms in your area</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredOffers as $offer)
                <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900">{{ $offer->title }}</h3>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ round($offer->savings) }}% off
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">{{ $offer->description }}</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">{{ $offer->gym_name }}</span>
                        <span class="text-red-600 font-medium">{{ $offer->expires_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Minimal Content Section -->
    <section class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Fitness guides</h2>
                <p class="text-gray-600">Expert tips to help you achieve your fitness goals</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php($guides = \App\Models\GymGuide::orderBy('id', 'desc')->get())
                @forelse($guides as $guide)
                <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors duration-200">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        {!! $guide->icon ?? '<svg class=\'h-5 w-5 text-blue-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><circle cx=12 cy=12 r=10 stroke=\'currentColor\' stroke-width=2 fill=\'none\'/></svg>' !!}
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $guide->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $guide->description }}</p>
                    @if($guide->link)
                        <a href="{{ $guide->link }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm" target="_blank">Read more →</a>
                    @endif
                </div>
                @empty
                <div class="col-span-full text-center text-gray-400">No guides available.</div>
                @endforelse
            </div>
        </div>
    </section>

    <script>
        // Simple performance optimizations
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth transitions for filter toggle
            const filterToggle = document.querySelector('[wire\\:click="toggleFilters"]');
            if (filterToggle) {
                filterToggle.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            }

            // Optimize facility checkboxes
            const facilityCheckboxes = document.querySelectorAll('input[wire\\:model\\.live\\.debounce\\.300ms="selectedFacilities"]');
            facilityCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.closest('label');
                    if (label) {
                        label.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            label.style.transform = 'scale(1)';
                        }, 150);
                    }
                });
            });
        });

        // Handle scroll to top on pagination
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</div> 