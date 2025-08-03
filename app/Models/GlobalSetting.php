<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        return $this->app_logo ? asset('storage/' . $this->app_logo) : asset('images/logo/logo.svg');
    }

    public function currency()
    {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id');
    }
}
