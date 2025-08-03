<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class MembershipActivityClass extends Model
{
    use BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'membership_id',
        'activity_class_id',
    ];

    /**
     * Get the membership that owns the activity class.
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the activity class that belongs to the membership.
     */
    public function activityClass(): BelongsTo
    {
        return $this->belongsTo(ActivityClass::class);
    }

    public function availableTimeSlots(): HasMany
    {
        return $this->hasMany(AvailableTimeSlot::class);
    }
}
