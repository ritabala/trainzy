<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\BelongsToGym;

class Payment extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'amount_paid',
        'transaction_no',
        'payment_date',
        'status',
        'payment_mode',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the user who made the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invoice associated with the payment
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope for payments in a specific year
     */
    public function scopeInYear(Builder $query, int $year): Builder
    {
        return $query->whereYear('payment_date', $year);
    }

    /**
     * Scope for membership payments with optional search
     */
    public function scopeMembershipPayments(Builder $query, ?string $search = null): Builder
    {
        return $query->select([
                'memberships.name as membership_name',
                'payments.payment_date',
                'payments.amount_paid',
                'payments.id'
            ])
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('membership_frequencies', 'invoice_details.membership_frequency_id', '=', 'membership_frequencies.id')
            ->join('memberships', 'membership_frequencies.membership_id', '=', 'memberships.id')
            ->whereNotNull('invoice_details.membership_frequency_id')
            ->when($search, function ($query, $search) {
                $query->where('memberships.name', 'like', '%' . $search . '%');
            });
    }

    /**
     * Scope for non-membership payments
     */
    public function scopeNonMembershipPayments(Builder $query): Builder
    {
        return $query->select([
                'payments.payment_date',
                'payments.amount_paid',
                'payments.id'
            ])
            ->leftJoin('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->where(function($query) {
                $query->whereNull('invoice_details.membership_frequency_id')
                    ->orWhereNull('invoices.id');
            });
    }
}
