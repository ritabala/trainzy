<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Traits\BelongsToGym;

class Membership extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'name',
        'tagline',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_memberships');
    }

    public function frequencies(): BelongsToMany
    {
        return $this->belongsToMany(Frequency::class, 'membership_frequencies')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'membership_services');
    }

    public function membershipFrequencies(): HasMany
    {
        return $this->hasMany(MembershipFrequency::class);
    }

    public function membershipServices(): HasMany
    {
        return $this->hasMany(MembershipService::class);
    }

    public function activityClasses(): BelongsToMany
    {
        return $this->belongsToMany(ActivityClass::class, 'membership_activity_classes');
    }

    public function membershipActivityClasses(): HasMany
    {
        return $this->hasMany(MembershipActivityClass::class);
    }

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function invoiceTaxes(): HasMany
    {
        return $this->hasMany(InvoiceTax::class);
    }

    public function userMemberships(): HasMany
    {
        return $this->hasMany(UserMembership::class);
    }

    public function scopeWithMembershipStats($query, $startDate, $endDate, $search = null)
    {
        return $query->withCount([
                // Count active users
                'userMemberships as active_customers' => function ($q) {
                    $q->where('membership_status', 'active')
                    ->whereColumn('users.latest_membership_id', 'user_memberships.id')
                    ->join('users', 'user_memberships.user_id', '=', 'users.id');
                },

                // Count lost users
                'userMemberships as lost_customers' => function ($q) {
                    $q->whereIn('membership_status', ['expired', 'cancelled'])
                    ->whereColumn('users.latest_membership_id', 'user_memberships.id')
                    ->join('users', 'user_memberships.user_id', '=', 'users.id');
                },

                // Count renewals using soft logic
                'userMemberships as renewals' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                    ->whereNotNull('parent_membership_id')
                    ->whereIn('membership_status', ['active', 'expired']);
                }
            ])
            ->when($search, fn($q) => 
                $q->where('name', 'like', "%{$search}%")
            );
    }

}
