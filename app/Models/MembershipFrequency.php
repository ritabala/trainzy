<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class MembershipFrequency extends Model
{
  use HasFactory, BelongsToGym;

  protected $fillable = [
    'membership_id',
    'frequency_id',
    'price'
  ];

  protected $casts = [
    'price' => 'decimal:2'
  ];

  public function membership(): BelongsTo
  {
    return $this->belongsTo(Membership::class);
  }

  public function invoiceDetails(): HasMany
  {
    return $this->hasMany(InvoiceDetail::class);
  }

  public function frequency(): BelongsTo
  {
    return $this->belongsTo(Frequency::class);
  }

  public function invoiceTaxes(): HasMany
  {
    return $this->hasMany(InvoiceTax::class);
  }
}
