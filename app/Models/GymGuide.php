<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymGuide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'link',
    ];
}
