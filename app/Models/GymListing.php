<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymListing extends Model
{
    protected $guarded = ['id'];

    const BADGES = [
        'free_trial',
        'verified',
        'trending',
        '24_7',
        'women_friendly',
    ];

    protected $appends = ['main_image_url'];

    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->images()->where('is_main', 1)->first();
        if ($mainImage) {
            return asset('storage/' . $mainImage->image);
        }
        return asset('images/listing/' . rand(1, 4) . '.jpeg');
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function gymFacilities()
    {
        return $this->hasMany(GymListingFacility::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'gym_listing_facilities', 'gym_listing_id', 'facility_id');
    }

    public function timings()
    {
        return $this->hasMany(GymListingTiming::class);
    }

    public function images()
    {
        return $this->hasMany(GymListingImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(GymReview::class);
    }
}
