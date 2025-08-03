<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class Service extends Model
{
  use HasFactory, BelongsToGym;

  protected $fillable = [
    'name',
    'is_active'
  ];

  protected $casts = [
    'is_active' => 'boolean'
  ];

  public function membershipServices(): HasMany
  {
    return $this->hasMany(MembershipService::class);
  }
}
