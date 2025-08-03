<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackagePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gym_id',
        'package_id',
        'subscription_id',
        'amount',
        'payment_gateway',
        'payment_status',
        'transaction_id',
        'paid_on',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_on' => 'datetime',
    ];

    /**
     * The payment statuses.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    /**
     * The payment gateways.
     */
    const GATEWAY_STRIPE = 'stripe';
    const GATEWAY_RAZORPAY = 'razorpay';

    /**
     * Get the gym that owns the payment.
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    /**
     * Get the package that owns the payment.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the subscription associated with the payment.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(GymPackageSubscription::class, 'subscription_id');
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', self::STATUS_FAILED);
    }

    /**
     * Scope a query to only include refunded payments.
     */
    public function scopeRefunded($query)
    {
        return $query->where('payment_status', self::STATUS_REFUNDED);
    }

    /**
     * Check if the payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the payment is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }

    /**
     * Check if the payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === self::STATUS_FAILED;
    }

    /**
     * Check if the payment is refunded.
     */
    public function isRefunded(): bool
    {
        return $this->payment_status === self::STATUS_REFUNDED;
    }
}
