<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalCurrency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'decimal_places',
        'decimal_point',
        'thousands_separator'
    ];

    protected $casts = [
        'decimal_places' => 'integer'
    ];

} 