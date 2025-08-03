<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToGym;
use App\Notifications\MembershipRenewed;

class UserMembership extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'user_id',
        'membership_id',
        // 'start_date',
        // 'end_date',
        // 'status',
        // 'payment_status',
        'membership_frequency_id',
        'membership_start_date',
        'membership_expiry_date',
        'membership_status',
        'auto_renewal',
        'last_renewal_date',
        'next_renewal_date',
        'parent_membership_id'
    ];

    protected $casts = [
        // 'start_date' => 'datetime',
        // 'end_date' => 'datetime',
        'membership_start_date' => 'date',
        'membership_expiry_date' => 'date',
        'last_renewal_date' => 'date',
        'next_renewal_date' => 'date',
    ];

    /**
     * Scope to get the latest membership for a user
     */
    public function scopeLatestForUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->latest('created_at');
    }

    /**
     * Scope to get historical memberships for a user
     */
    public function scopeHistoricalForUser(Builder $query, $userId, $excludeId = null)
    {
        $query = $query->where('user_id', $userId);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the default relationships to eager load
     */
    public static function defaultEagerLoad()
    {
        return [
            'membership' => function($query) {
                $query->select('memberships.*');
            },
            'membershipFrequency' => function($query) {
                $query->select('membership_frequencies.*');
            },
            'membershipFrequency.frequency' => function($query) {
                $query->select('frequencies.*');
            },
            'membership.services' => function($query) {
                $query->select('services.*');
            }
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function membershipFrequency(): BelongsTo
    {
        return $this->belongsTo(MembershipFrequency::class, 'membership_frequency_id');
    }

    public function membershipServices()
    {
        return $this->hasMany(MembershipService::class);
    }

    public static function getMembershipStatuses()
    {
        return [
            'active' => __('membership.user_membership_status.active'),
            'expired' => __('membership.user_membership_status.expired'),
            'cancelled' => __('membership.user_membership_status.cancelled'),
            'suspended' => __('membership.user_membership_status.suspended'),
        ];
    }

    public function parentMembership()
    {
        return $this->belongsTo(UserMembership::class, 'parent_membership_id');
    }

    public function childMembership(): HasOne
    {
        return $this->hasOne(UserMembership::class, 'parent_membership_id', 'id');
    }

    public function calculateDate($frequencyName, $startDate)
    {
        $endDate = $startDate->copy();
        switch ($frequencyName) {
            case 'weekly':
                $endDate->addWeek()->subDay();
                break;
            case 'monthly':
                $endDate->addMonth()->subDay();
                break;
            case 'quarterly':
                $endDate->addMonths(3)->subDay();
                break;
            case 'half-yearly':
                $endDate->addMonths(6)->subDay();
                break;
            case 'yearly':
                $endDate->addYear()->subDay();
                break;
            case 'daily':
                $endDate->addDay();
                break;
            default:
                $endDate->addMonth()->subDay(); // Default to monthly
        }
        return $endDate;
    }

    public function renew()
    {
        $this->membership_status = 'expired';
        $this->save();
        
        $newMembership = $this->replicate();
        $newMembership->membership_status = 'active';
        $newMembership->membership_start_date = now();
        $newMembership->parent_membership_id = $this->id;
        $newMembership->membership_expiry_date = $this->calculateDate($this->membershipFrequency->frequency->slug, now());

        $newMembership->last_renewal_date = $this->membership_start_date;

        $newMembership->next_renewal_date = $newMembership->membership_expiry_date->addDays(1);
        
        $newMembership->save();

        // Send notification to the user
        $user = $newMembership->user;
        $user->notify(new MembershipRenewed($newMembership));
    }

    public function activateUpcoming()
    {
        $this->membership_status = 'active';
        $this->save();

        $parentMembership = $this->parentMembership;
        if ($parentMembership) {
            $parentMembership->membership_status = 'expired';
            $parentMembership->save();
        }
    }
}
