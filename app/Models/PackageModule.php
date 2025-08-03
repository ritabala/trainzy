<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageModule extends Model
{
    protected $fillable = [
        'package_id',
        'module_id'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
} 