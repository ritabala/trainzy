<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymListingImage extends Model
{
    protected $fillable = ['gym_listing_id', 'image', 'is_main'];

    protected $appends = ['image_url'];
    
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

    public function gymListing()
    {
        return $this->belongsTo(GymListing::class);
    }
}
