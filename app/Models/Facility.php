<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'icon'];

    public function gymListings()
    {
        return $this->belongsToMany(GymListing::class);
    }
}
