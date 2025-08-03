<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymListingFacility extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;
    
    public function gymListing()
    {
        return $this->belongsTo(GymListing::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
