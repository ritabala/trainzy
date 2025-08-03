<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\BelongsToGym;

class Tax extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['tax_name', 'tax_percent'];

    public function invoiceTaxes()
    {
        return $this->hasMany(InvoiceTax::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_taxes');
    }
}
