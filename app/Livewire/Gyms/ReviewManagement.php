<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\GymListing;
use App\Models\GymReview;

class ReviewManagement extends Component
{
    public $listingId;
    public $reviewsToShow = 5;
    public $reviewToDelete = null;
    public $showDeleteReviewModal = false;
    public $listing;

    protected $listeners = ['refreshReviews' => '$refresh'];

    public function mount($listingId)
    {
        $this->listingId = $listingId;
        $this->listing = GymListing::with(['reviews.user'])->findOrFail($listingId);
    }

    public function loadMoreReviews()
    {
        $this->reviewsToShow += 5;
    }

    public function deleteReview($reviewId)
    {
        $this->reviewToDelete = $reviewId;
        $this->showDeleteReviewModal = true;
    }

    public function confirmDeleteReview()
    {
        if ($this->reviewToDelete) {
            $review = GymReview::where('gym_listing_id', $this->listingId)->find($this->reviewToDelete);
            if ($review) {
                $review->delete();
                session()->flash('message', 'Review deleted successfully!');
            }
        }
        $this->showDeleteReviewModal = false;
        $this->reviewToDelete = null;
        $this->listing = GymListing::with(['reviews.user'])->findOrFail($this->listingId);
        $this->emitSelf('refreshReviews');
    }

    public function cancelDeleteReview()
    {
        $this->showDeleteReviewModal = false;
        $this->reviewToDelete = null;
    }

    public function getReviewsProperty()
    {
        return $this->listing->reviews->sortByDesc('created_at')->take($this->reviewsToShow);
    }

    public function render()
    {
        return view('livewire.gyms.review-management', [
            'listing' => $this->listing,
            'reviews' => $this->reviews,
            'reviewsToShow' => $this->reviewsToShow,
        ]);
    }
} 