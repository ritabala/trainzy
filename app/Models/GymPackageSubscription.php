<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToGym;

class GymPackageSubscription extends Model
{
    use HasFactory;
    use BelongsToGym;

    protected $guarded = ['id'];

    protected $casts = [
        'starts_on' => 'datetime',
        'ends_on' => 'datetime',
        'expires_on' => 'datetime',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the gym that owns the subscription.
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    /**
     * Get the package that owns the subscription.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the package payment associated with the subscription.
     */
    public function packagePayment(): BelongsTo
    {
        return $this->belongsTo(PackagePayment::class, 'id', 'subscription_id');
    }
}
