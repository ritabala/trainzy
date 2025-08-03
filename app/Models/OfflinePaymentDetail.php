<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfflinePaymentDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'global_payment_gateway_id',
        'package_id',
        'gym_id',
        'document_path',
        'description',
        'amount',
        'is_accepted',
        'accepted_at',
        'billing_cycle',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'is_accepted' => 'boolean',
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the payment gateway that owns the offline payment detail.
     */
    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(GlobalPaymentGateway::class, 'global_payment_gateway_id');
    }

    /**
     * Get the package that owns the offline payment detail.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the gym that owns the offline payment detail.
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
} 