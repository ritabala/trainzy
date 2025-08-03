<div class="min-h-screen bg-white">
    <!-- Hero Section with Background Image -->
    <section class="relative bg-white border-b border-gray-100">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $images[$currentImageIndex] }}');">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        
        <!-- Image Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-50">
            @foreach($images as $index => $image)
            <button type="button" wire:click="goToImage({{ $index }})" class="w-3 h-3 rounded-full {{ $index === $currentImageIndex ? 'bg-white' : 'bg-white/50' }} transition-colors duration-200"></button>
            @endforeach
        </div>
        
        <!-- Image Navigation Buttons -->
        <button type="button" wire:click="previousImage" wire:loading.attr="disabled" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm rounded-full p-3 shadow-lg z-50 hover:bg-white transition-colors duration-200 cursor-pointer disabled:opacity-50">
            <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button type="button" wire:click="nextImage" wire:loading.attr="disabled" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm rounded-full p-3 shadow-lg z-50 hover:bg-white transition-colors duration-200 cursor-pointer disabled:opacity-50">
            <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badges -->
                <div class="flex justify-center items-center gap-2 mb-6">
                    @if(in_array('free_trial', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">Free Trial</span>
                    @endif
                    @if(in_array('verified', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">Verified</span>
                    @endif
                    @if(in_array('trending', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">Trending</span>
                    @endif
                    @if(in_array('sponsored', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-medium">Sponsored</span>
                    @endif
                    @if(in_array('24_7', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">24/7</span>
                    @endif
                    @if(in_array('women_friendly', json_decode($gym->gymListings->badges, true)))
                        <span class="bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-medium">Women Friendly</span>
                    @endif
                </div>
                
                <!-- Gym Name -->
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    {{ $gym->name }}
                </h1>
                
                <!-- Rating and Location -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-8 text-white/90">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="font-semibold">{{ round($averageRating, 1) }}</span>
                        <span class="text-sm">({{ $reviews->count() }} reviews)</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $gym['address'] }}</span>
                        <span class="text-sm">• {{ $distance }} km away</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="tel:{{ $gym->phone }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition-colors duration-200 font-medium text-sm">
                        Call Now
                    </a>
                    <a href="https://maps.google.com/maps?q={{ $gym->gymListings->latitude }},{{ $gym->gymListings->longitude }}" target="_blank" class="bg-white/20 backdrop-blur-sm border border-white/30 text-white px-8 py-3 rounded-xl transition-colors duration-200 font-medium text-sm hover:bg-white/30">
                        View on Map
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- About Section -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $gym->gymListings->about }}</p>
                </div>

                <!-- Facilities Section -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Facilities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($gym->gymListings->gymFacilities as $facility)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                            <span class="text-2xl">{{ $facility->facility->icon }}</span>
                            <div>
                                <div class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $facility->facility->name)) }}</div>
                                <div class="text-sm text-gray-500">{{ $facility->facility->description }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Membership Plans -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Membership Plans</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($gym->memberships as $plan)
                        <div class="relative group">
                            <div class="border border-gray-200 rounded-xl p-6 h-full flex flex-col {{ $plan['popular'] ? 'ring-2 ring-blue-500 bg-blue-50' : 'bg-white hover:shadow-lg' }} transition-all duration-300">
                                @if($plan['popular'])
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg">
                                    ⭐ Most Popular
                                </div>
                                @endif
                                
                                <div class="text-center mb-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                    <div class="mb-4">
                                        <div class="flex items-baseline justify-center gap-1">
                                            <span class="text-xl font-bold text-gray-900">${{ $plan->membershipFrequencies->first()->price }}</span>
                                            <span class="text-base text-gray-500">/{{ $plan->frequencies->first()->name }}</span>
                                        </div>
                                        @if($plan->frequencies->first()->name == 'month')
                                        <div class="text-sm text-gray-500 mt-1">Billed monthly</div>
                                        @elseif($plan->frequencies->first()->name == 'year')
                                        <div class="text-sm text-green-600 font-medium mt-1">Save 20% annually</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="space-y-3 mb-6">
                                        @foreach($plan->services as $service)
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-700">{{ $service->name }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Reviews</h2>
                        <button wire:click="$set('showReviewForm', true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Leave a Review
                        </button>
                    </div>

                    <!-- Review Summary -->
                    <div class="flex items-center gap-8 mb-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ round($averageRating, 1) }}</div>
                            <div class="flex items-center justify-center mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-500">{{ $reviews->count() }} reviews</div>
                        </div>
                        
                        <div class="flex-1">
                            @foreach($ratingBreakdown as $star => $count)
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm text-gray-600 w-8">{{ $star }}★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ round($count / $reviews->count() * 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-500 w-8">{{ $count }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Review List -->
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ $review->user ? $review->user->name : 'Anonymous' }}</span>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="font-medium text-gray-900 mb-1">{{ $review->title }}</div>
                            <div class="text-gray-600 text-sm">{{ $review->review }}</div>
                            @if(isset($review->tags))
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach(json_decode($review->tags, true) as $tag)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{{ ucfirst($tag) }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-80 flex-shrink-0">
                <div class="sticky top-8 space-y-6">
                    <!-- Opening Hours -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Opening Hours</h3>
                        <div class="space-y-2">
                            @foreach($gym->gymListings->timings as $timing)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-gray-600">{{ ucfirst($timing->day) }}</span>
                                <span class="font-medium text-gray-900">{{ $timing->open_time }} - {{ $timing->close_time }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <div class="text-gray-900">{{ $gym->gymListings->address }}</div>
                                    <div class="text-sm text-gray-500">{{ $distance }} km from you</div>
                                </div>
                            </div>
                            <a href="https://maps.google.com/maps/dir/?api=1&destination={{ $gym->gymListings->latitude }},{{ $gym->gymListings->longitude }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm hover:shadow-md">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                                </svg>
                                Get Directions
                            </a>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact</h3>
                        <div class="space-y-3">
                            <a href="tel:{{ $gym->phone }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $gym->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Form Modal -->
    @if($showReviewForm)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Leave a Review</h3>
                    <button wire:click="hideReviewForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitReview">
                    <!-- Rating Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">How would you rate your experience?</label>
                        <div class="flex items-center justify-center gap-2 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('reviewForm.rating', {{ $i }})" class="flex flex-col items-center gap-1 p-3 rounded-lg transition-all {{ $i == $reviewForm['rating'] ? 'bg-blue-50 border-2 border-blue-500' : 'bg-gray-50 border-2 border-gray-200 hover:bg-gray-100' }}">
                                <span class="text-2xl {{ $i == $reviewForm['rating'] ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                <span class="text-xs font-medium {{ $i == $reviewForm['rating'] ? 'text-blue-700' : 'text-gray-600' }}">{{ $this->getRatingLabel($i) }}</span>
                            </button>
                            @endfor
                        </div>
                        <div class="text-center">
                            <span class="text-sm font-semibold text-gray-900">{{ $this->getRatingLabel($reviewForm['rating']) }}</span>
                            <span class="text-xl ml-2 text-yellow-400">{{ $reviewForm['rating'] }}★</span>
                        </div>
                    </div>

                    <!-- Review Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-2">Review Title</label>
                        <input type="text" wire:model="reviewForm.title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Summarize your experience...">
                        @error('reviewForm.title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Review Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-2">Tell us about your experience</label>
                        <textarea wire:model="reviewForm.content" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="What you liked, what could be better..."></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-500">{{ strlen($reviewForm['content']) }}/500 characters</span>
                            @error('reviewForm.content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">What did you like/dislike?</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(\App\Models\GymReview::TAGS as $key => $category)
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" value="{{ $category }}" wire:model.live='reviewForm.categories.{{ $key }}' class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ ucfirst($category) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            Post Review
                        </button>
                        <button type="button" wire:click="hideReviewForm" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Toast -->
    @if(session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('message') }}
    </div>
    @endif

    <!-- Sticky Bottom Bar (Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white shadow-lg border-t border-gray-200 flex items-center justify-between px-4 py-3 md:hidden">
        <div>
            <div class="font-bold text-gray-900">{{ $gym->name }}</div>
            <div class="flex items-center gap-1 text-xs text-gray-500">
                <span class="text-yellow-400">★</span>
                <span>{{ round($averageRating, 1) }}</span>
                <span>• {{ $distance }} km</span>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="tel:{{ $gym->phone }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-medium">Call Now</a>
        </div>
    </div>
</div> 