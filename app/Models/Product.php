<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class Product extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['name', 'product_code', 'price', 'quantity', 'expiry_date', 'description'];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes');
    }
}
