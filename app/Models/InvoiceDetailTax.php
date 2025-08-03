<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToGym;

class InvoiceDetailTax extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'invoice_detail_id',
        'tax_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceDetail::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}
