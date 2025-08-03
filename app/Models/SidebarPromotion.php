<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarPromotion extends Model
{
    protected $fillable = [
        'type', 'title', 'description', 'expires_in', 'trainer_count', 'savings'
    ];
}
