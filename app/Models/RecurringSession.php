<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\BelongsToGym;

class RecurringSession extends Model
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
        'start_time',
        'end_time',
        'status',
        'recurrence_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the member (user) that owns the recurring session.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the time slot that the recurring session is based on.
     */
    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(AvailableTimeSlot::class, 'available_time_slot_id');
    }

    /**
     * Scope a query to only include scheduled sessions.
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include cancelled sessions.
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include updated sessions.
     */
    public function scopeUpdated(Builder $query): Builder
    {
        return $query->where('status', 'updated');
    }

    /**
     * Scope a query to only include sessions of a specific recurrence type.
     */
    public function scopeOfRecurrenceType(Builder $query, string $type): Builder
    {
        return $query->where('recurrence_type', $type);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($session) {
            // Ensure the user is a member
            if ($session->member && $session->member->role !== 'member-' . $session->gym_id) {
                throw new \InvalidArgumentException(__('recurring_sessions.validation.member_role'));
            }

            // Ensure end_time is after start_time
            if ($session->end_time <= $session->start_time) {
                throw new \InvalidArgumentException(__('recurring_sessions.validation.time_error'));
            }
        });
    }
} 