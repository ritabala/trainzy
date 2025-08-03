<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\BelongsToGym;

class MemberEnrollment extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'available_time_slot_id',
        'enrolled_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /**
     * Get the member (user) that owns the enrollment.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the time slot that the member is enrolled in.
     */
    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(AvailableTimeSlot::class, 'available_time_slot_id');
    }

    /**
     * Scope a query to only include active enrollments.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'enrolled');
    }

    /**
     * Scope a query to only include cancelled enrollments.
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($enrollment) {
            // Ensure the user is a member
            if ($enrollment->member && !$enrollment->member->hasRole('member-' . $enrollment->member->gym_id)) {
                throw new \InvalidArgumentException(__('enrollment.validation.member_role'));
            }
        });
    }
} 