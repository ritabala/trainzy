<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymReview extends Model
{
    protected $guarded = ['id'];

    const TAGS = [
        'cleanliness',
        'equipment',
        'staff',
        'atmosphere',
        'price',
    ];
    
    public function gymListing()
    {
        return $this->belongsTo(GymListing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
