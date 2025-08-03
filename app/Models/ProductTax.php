<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\BelongsToGym;

class ProductTax extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['product_id', 'tax_id'];

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}