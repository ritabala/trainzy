@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('gym-listings.reviews.index', $listing) }}" 
                   class="mr-4 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add Review</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Share your experience with {{ $listing->gym->name }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-8">
                <form action="{{ route('gym-listings.reviews.store', $listing) }}" method="POST">
                    @csrf
                    
                    <!-- Rating Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="{{ $i }}" 
                                           class="sr-only" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center cursor-pointer hover:border-yellow-400 transition-colors rating-star" data-rating="{{ $i }}">
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </label>
                            @endfor
                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Click to rate</span>
                        </div>
                        @error('rating') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Title Field -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Review Title (optional)
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                               placeholder="Give your review a title..." 
                               value="{{ old('title') }}">
                        @error('title') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Review Field -->
                    <div class="mb-6">
                        <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Your Review <span class="text-red-500">*</span>
                        </label>
                        <textarea id="review" name="review" rows="6" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                                  placeholder="Share your experience with this gym...">{{ old('review') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tell others about your experience, what you liked, and what could be improved.
                        </p>
                        @error('review') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Tags Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Tags (optional)
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(\App\Models\GymReview::TAGS as $tag)
                                <label class="flex items-center">
                                    <input type="checkbox" name="tags[]" value="{{ $tag }}" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array($tag, old('tags', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $tag) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Select relevant tags to help others find your review.
                        </p>
                        @error('tags') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('gym-listings.reviews.index', $listing) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');

    ratingStars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = index + 1;
            
            // Update visual stars
            ratingStars.forEach((s, i) => {
                const svg = s.querySelector('svg');
                if (i < rating) {
                    s.classList.add('border-yellow-400');
                    svg.classList.remove('text-gray-300');
                    svg.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('border-yellow-400');
                    svg.classList.remove('text-yellow-400');
                    svg.classList.add('text-gray-300');
                }
            });
            
            // Check the corresponding radio input
            ratingInputs[index].checked = true;
        });
    });

    // Initialize stars based on old input
    const selectedRating = document.querySelector('input[name="rating"]:checked');
    if (selectedRating) {
        const rating = parseInt(selectedRating.value);
        ratingStars.forEach((star, index) => {
            const svg = star.querySelector('svg');
            if (index < rating) {
                star.classList.add('border-yellow-400');
                svg.classList.remove('text-gray-300');
                svg.classList.add('text-yellow-400');
            }
        });
    }
});
</script>
@endsection