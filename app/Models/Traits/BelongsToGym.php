<?php

namespace App\Models\Traits;

use App\Models\Scopes\GymScope;
use App\Models\Gym;
trait BelongsToGym
{
    protected static function booted()
    {
        static::addGlobalScope(new GymScope);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
} 