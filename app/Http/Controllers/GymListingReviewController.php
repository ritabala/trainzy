<?php

namespace App\Http\Controllers;

use App\Models\GymListing;
use App\Models\GymReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymListingReviewController extends Controller
{
    public function index(GymListing $listing)
    {
        
        $reviews = $listing->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('gym-listings.reviews.index', compact('listing', 'reviews'));
    }

    public function create(GymListing $listing)
    {        
        return view('gym-listings.reviews.create', compact('listing'));
    }

    public function store(Request $request, GymListing $listing)
    {
        
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string'
        ]);

        $review = $listing->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'review' => $validated['review'],
            'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
        ]);

        return redirect()->route('gym-listings.reviews.index', $listing)
            ->with('message', 'Review submitted successfully!');
    }

    public function edit(GymListing $listing, GymReview $review)
    {
        return view('gym-listings.reviews.edit', compact('listing', 'review'));
    }

    public function update(Request $request, GymListing $listing, GymReview $review)
    {        
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|max:1000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:' . implode(',', GymReview::TAGS)
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'review' => $validated['review'],
            'tags' => $validated['tags'] ? json_encode($validated['tags']) : null,
        ]);

        return redirect()->route('gym-listings.reviews.index', $listing)
            ->with('message', 'Review updated successfully!');
    }

    public function destroy(GymListing $listing, GymReview $review)
    {        
        $review->delete();
        
        return redirect()->route('gym-listings.reviews.index', $listing)
            ->with('message', 'Review deleted successfully!');
    }
} 