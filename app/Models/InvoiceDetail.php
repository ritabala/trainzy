<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class InvoiceDetail extends Model
{
    use HasFactory, BelongsToGym;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function membershipFrequency(): BelongsTo
    {
        return $this->belongsTo(MembershipFrequency::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(InvoiceDetailTax::class);
    }

}
