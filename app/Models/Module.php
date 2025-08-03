<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'status',
        'is_additional'
    ];

    protected $casts = [
        'is_additional' => 'boolean'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_modules')
            ->withTimestamps();
    }
} 