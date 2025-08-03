<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToGym;

class Currency extends Model
{
    use BelongsToGym;

    protected $fillable = [
        'gym_id',
        'name',
        'code',
        'symbol',
        'decimal_places',
        'decimal_point',
        'thousands_separator'
    ];

    protected $casts = [
        'decimal_places' => 'integer'
    ];

    public function gyms()
    {
        return $this->hasMany(Gym::class);
    }
}
