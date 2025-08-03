<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymListingTiming extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function gymListing()
    {
        return $this->belongsTo(GymListing::class);
    }
}
