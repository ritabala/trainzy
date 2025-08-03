<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class BodyMetricType extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'unit',
        'is_active',
        'display_order'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Get the body measurements for this metric type.
     */
    public function bodyMeasurementValues(): HasMany
    {
        return $this->hasMany(BodyMeasurementValue::class);
    }
}
