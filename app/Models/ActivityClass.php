<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;


class ActivityClass extends Model
{
    use BelongsToGym;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'duration',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the memberships that belong to the activity class.
     */
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class, 'membership_activity_classes')
            ->withTimestamps();
    }

    /**
     * Get the available time slots for the activity class.
     */
    public function availableTimeSlots(): HasMany
    {
        return $this->hasMany(AvailableTimeSlot::class);
    }
}
