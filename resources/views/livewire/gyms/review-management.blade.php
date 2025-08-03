<div>
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ $review->user ? substr($review->user->name, 0, 1) : 'A' }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $review->user ? $review->user->name : 'Anonymous' }}
                            </p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-3 w-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $review->created_at->diffForHumans() }}
                        </span>
                        <button wire:click="deleteReview({{ $review->id }})" class="ml-2 px-2 py-1 bg-red-600 text-white text-xs rounded-lg">Delete</button>
                    </div>
                </div>
                @if($review->title)
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $review->title }}</h4>
                @endif
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $review->review }}</p>
                @if($review->tags)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach(json_decode($review->tags, true) ?? [] as $tag)
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs rounded">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">No reviews yet</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Be the first to review this gym!</p>
            </div>
        @endforelse
    </div>
    @if($listing->reviews->count() > $reviewsToShow)
        <div class="mt-4 text-center">
            <button wire:click="loadMoreReviews" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                Load more reviews
            </button>
        </div>
    @endif

    <!-- Delete Review Modal -->
    @if($showDeleteReviewModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="cancelDeleteReview">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800" wire:click.stop>
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Delete Review</h3>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">Are you sure you want to delete this review?</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="cancelDeleteReview" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</button>
                        <button wire:click="confirmDeleteReview" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        </div>
    @endif
</div> 