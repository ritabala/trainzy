<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\Traits\BelongsToGym;

class AvailableTimeSlot extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gym_id',
        'activity_class_id',
        'user_id',
        'start_time',
        'end_time',
        'status',
        'day_of_week',
        'date', 
        'type',
        'status'
    ];

     /**
     * Accessor for start_time (returns Carbon instance).
     */
    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * Accessor for end_time (returns Carbon instance).
     */
    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * Mutator for start_time (saves as 'H:i').
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value)->format('H:i');
    }

    /**
     * Mutator for end_time (saves as 'H:i').
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value)->format('H:i');
    }

    /**
     * Get the activity class that owns the time slot.
     */
    public function activityClass(): BelongsTo
    {
        return $this->belongsTo(ActivityClass::class);
    }

    /**
     * Get the instructor (staff user) assigned to the time slot.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the member enrollments for this time slot.
     */
    public function memberEnrollments(): HasMany
    {
        return $this->hasMany(MemberEnrollment::class, 'available_time_slot_id');
    }

    /**
     * Get the recurring sessions based on this time slot.
     */
    public function recurringSessions(): HasMany
    {
        return $this->hasMany(RecurringSession::class, 'available_time_slot_id');
    }

    /**
     * Scope a query to only include time slots with staff instructors.
     */
    public function scopeWithStaffInstructors(Builder $query): Builder
    {
        $currentGym = gym();
        
        // If no gym is available, return empty query
        if (!$currentGym) {
            return $query->whereRaw('1 = 0'); // Return no results
        }

        return $query->whereHas('instructor', function ($query) use ($currentGym) {
            $query->where('is_active', true)
              ->whereHas('roles', function ($roleQuery) use ($currentGym) {
                  $roleQuery->whereIn('name', ['staff-' . $currentGym->id, 'admin-' . $currentGym->id]);
              });
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($timeSlot) {
            // Get the current gym
            $currentGym = gym();
            
            // If no gym is available, skip validation
            if (!$currentGym) {
                return;
            }

            // Ensure the instructor is a staff member
            if ($timeSlot->instructor && 
                (!$timeSlot->instructor->hasAnyRole(['staff-' . $currentGym->id, 'admin-' . $currentGym->id]) || !$timeSlot->instructor->is_active)
            ) {
                throw new \InvalidArgumentException(__('time_slots.validation.staff_role'));
            }
        });
    }
} 