<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frequency extends Model
{
  use HasFactory;

  protected $appends = ['duration_in_days'];
  protected $fillable = [
    'name',
    'slug',
    'description'
  ];

  public function getDurationInDaysAttribute(): int
  {

    // Subtract 1 day because the membership is active for the first day
    switch ($this->slug) {
      case 'daily':
        return 1;
      case 'weekly':
        return 6;
      case 'monthly':
        return 29;
      case 'quarterly':
        return 89;
      case 'half-yearly':
        return 179;
      case 'yearly':
        return 364;
    }
  }

  public function membershipFrequencies(): HasMany
  {
    return $this->hasMany(MembershipFrequency::class);
  }
}
