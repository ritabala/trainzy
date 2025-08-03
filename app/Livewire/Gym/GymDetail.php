<?php

namespace App\Livewire\Gym;

use Livewire\Component;
use App\Models\Gym;
use App\Models\Review;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\Facility;
use App\Models\GymListing;
use App\Models\GymReview;

class GymDetail extends Component
{
    public $gym;
    public $slug;
    public $currentImageIndex = 0;
    public $showAllFacilities = false;
    public $selectedMembershipPlan = null;
    public $reviewFilter = 'all';
    public $userLocation = null;
    public $distance = null;
    
    // Gallery
    public $images = [];
    public $videos = [];
    
    // Reviews
    public $reviews = [];
    public $averageRating = 0;
    public $ratingBreakdown = [];
    
    // Similar gyms
    public $similarGyms = [];
    
    // Membership plans
    public $membershipPlans = [];
    
    // Trainers
    public $trainers = [];
    
    // Facilities
    public $facilities = [];
    
    public $showReviewForm = false;
    public $reviewForm = [
        'rating' => 5,
        'title' => '',
        'content' => '',
        'categories' => [],
        'photos' => []
    ];
    
    protected $rules = [
        'reviewForm.rating' => 'required|integer|min:1|max:5',
        'reviewForm.title' => 'required|string|min:3|max:100',
        'reviewForm.content' => 'required|string|min:10|max:500',
        'reviewForm.categories' => 'array',
    ];

    protected $messages = [
        'reviewForm.rating.required' => 'Please select a rating.',
        'reviewForm.title.required' => 'Please enter a review title.',
        'reviewForm.title.min' => 'Title must be at least 3 characters.',
        'reviewForm.content.required' => 'Please enter your review.',
        'reviewForm.content.min' => 'Review must be at least 10 characters.',
        'reviewForm.content.max' => 'Review cannot exceed 500 characters.',
    ];

    protected $listeners = ['locationUpdated' => 'updateLocation'];

    public $categories = [];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadGymData();
        $this->loadGallery();
        $this->loadReviews();
        $this->loadSimilarGyms();
        $this->calculateDistance();
        $this->categories = GymReview::TAGS;
    }

    public function loadGymData()
    {
        $this->gym = Gym::where('slug', $this->slug)
                ->with('gymListings', 'gymListings.gymFacilities', 'gymListings.timings', 'gymListings.reviews', 'gymListings.gymFacilities.facility', 'gymListings.images', 'memberships', 'memberships.frequencies', 'memberships.services', 'memberships.membershipFrequencies')
                ->firstOrFail();

    }

    public function loadGallery()
    {
        if ($this->gym->gymListings->images->count() > 0) {
            $this->images = $this->gym->gymListings->images->pluck('image_url')->toArray();
        } else {
            $this->images = [
                asset('images/listing/1.jpeg'),
                asset('images/listing/2.jpeg'),
                asset('images/listing/3.jpeg'),
                asset('images/listing/4.jpeg'),
            ];
        }
        
    }

    public function loadReviews()
    {
        if ($this->reviewFilter != 'all') {
            // Get all reviews for all gym listings for this gym, filtered by tag
            $this->reviews = GymReview::whereHas('gymListing', function ($query) {
                    $query->where('gym_listing_id', $this->gym->gymListings->id);
                })
                ->where('tags', 'like', '%' . $this->reviewFilter . '%')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Get all reviews for all gym listings for this gym
            $this->reviews = GymReview::whereHas('gymListing', function ($query) {
                    $query->where('gym_listing_id', $this->gym->gymListings->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $this->averageRating = $this->reviews->avg('rating');
        $this->ratingBreakdown = [
            5 => $this->reviews->where('rating', 5)->count(),
            4 => $this->reviews->where('rating', 4)->count(),
            3 => $this->reviews->where('rating', 3)->count(),
            2 => $this->reviews->where('rating', 2)->count(),
            1 => $this->reviews->where('rating', 1)->count()
        ];
    }

    public function loadSimilarGyms()
    {
        $this->similarGyms = [
            [
                'id' => 2,
                'name' => 'PowerHouse Gym',
                'rating' => 4.5,
                'reviews' => 180,
                'distance' => 0.8,
                'price' => 39.99,
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'sponsored' => false
            ],
            [
                'id' => 3,
                'name' => 'Elite Fitness Center',
                'rating' => 4.8,
                'reviews' => 95,
                'distance' => 1.5,
                'price' => 69.99,
                'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'sponsored' => true
            ],
            [
                'id' => 4,
                'name' => 'Community Wellness',
                'rating' => 4.3,
                'reviews' => 120,
                'distance' => 2.1,
                'price' => 44.99,
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'sponsored' => false
            ]
        ];
    }

    public function calculateDistance()
    {
        // In production, this would calculate actual distance from user location
        $this->distance = 1.2; // km
    }

    public function updateLocation($location)
    {
        $this->userLocation = $location;
        $this->calculateDistance();
    }

    public function nextImage()
    {
        $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->images);
    }

    public function previousImage()
    {
        $this->currentImageIndex = $this->currentImageIndex === 0 ? count($this->images) - 1 : $this->currentImageIndex - 1;
    }

    public function goToImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function filterReviews($filter)
    {
        $this->reviewFilter = $filter;
        $this->loadReviews();
    }

    public function toggleFacilities()
    {
        $this->showAllFacilities = !$this->showAllFacilities;
    }

    public function selectMembershipPlan($planId)
    {
        $this->selectedMembershipPlan = $planId;
    }

    public function getRatingLabel($rating)
    {
        return match($rating) {
            1 => 'Terrible',
            2 => 'Poor',
            3 => 'Average',
            4 => 'Good',
            5 => 'Excellent',
            default => 'Select Rating'
        };
    }

    public function getRatingEmoji($rating)
    {
        return match($rating) {
            1 => '😡',
            2 => '😞',
            3 => '😐',
            4 => '😊',
            5 => '😍',
            default => '⭐'
        };
    }

    public function showReviewForm()
    {
        $this->showReviewForm = true;
    }

    public function hideReviewForm()
    {
        $this->showReviewForm = false;
        $this->resetReviewForm();
    }

    public function resetReviewForm()
    {
        $this->reviewForm = [
            'rating' => 5,
            'title' => '',
            'content' => '',
            'categories' => [],
            'photos' => []
        ];
    }

    public function toggleCategory($category)
    {
        if (in_array($category, $this->reviewForm['categories'])) {
            $this->reviewForm['categories'] = array_diff($this->reviewForm['categories'], [$category]);
        } else {
            $this->reviewForm['categories'][] = $category;
        }
    }

    public function submitReview()
    {
        $this->validate();

        $tags = [];
        foreach ($this->reviewForm['categories'] as $key => $category) {
            $tags[] = GymReview::TAGS[$key];
        }

        $newReview = GymReview::create([
            'gym_listing_id' => $this->gym->gymListings->id,
            'rating' => $this->reviewForm['rating'],
            'title' => $this->reviewForm['title'],
            'review' => $this->reviewForm['content'],
            'tags' => json_encode($tags)
        ]);

        // Update average rating
        $this->updateAverageRating();

        // Reset form and hide
        $this->hideReviewForm();

        $this->loadReviews();

        session()->flash('message', 'Thanks! Your review is now live.');
    }

    public function updateAverageRating()
    {
        $totalRating = 0;
        $totalReviews = count($this->reviews);
        
        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }
        
        $this->averageRating = $totalReviews > 0 ? round($totalRating / $totalReviews, 1) : 0;
        
        // Update rating breakdown
        $this->ratingBreakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($this->reviews as $review) {
            $this->ratingBreakdown[$review['rating']]++;
        }
    }

    public function render()
    {
        return view('livewire.gym.gym-detail');
    }
} 