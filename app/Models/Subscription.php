<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToGym;

class Subscription extends Model
{
    use BelongsToGym;

    protected $guarded = ['id'];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime'
    ];
}
