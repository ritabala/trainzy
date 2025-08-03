<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatestOffer extends Model
{
    protected $fillable = [
        'type', 'title', 'description', 'expires_at', 'trainer_count', 'savings'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
